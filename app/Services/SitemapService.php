<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * This class generates and writes XML sitemap files.
 */
class SitemapService
{
    /** @var array[] Sitemap configuration */
    private array $conf;

    /** @var string Base site URL */
    private string $home;

    /** @var Carbon Timestamp of generation */
    private Carbon $now;

    /** @var string Default locale */
    private string $appLocale;

    /** @var array<string, array<string, bool>> Cache for column existence checks */
    private array $columnCache = [];

    /**
     * SitemapService constructor.
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->conf = [
            'index' => [
                'path' => public_path('sitemap.xml'),
                'url'  => url('sitemap.xml'),
            ],
            'materials' => [
                'path' => public_path('materials.sitemap.xml'),
                'url'  => url('materials.sitemap.xml'),
            ],
            'consumers' => [
                'path' => public_path('consumers.sitemap.xml'),
                'url'  => url('consumers.sitemap.xml'),
            ],
        ];

        $this->home = url('/');
        $this->now = now();
        $this->appLocale = config('app.fallback_locale');

        foreach ($this->conf as $scope) {
            $path = $scope['path'];
            if (!file_exists($path)) {
                touch($path);
            }
            if (!is_writable($path)) {
                throw new \RuntimeException("File {$path} not writable");
            }
        }
    }

    /**
     * Generate all sitemap files.
     *
     * @return void
     * @throws \DOMException
     */
    public function writeSitemap(): void
    {
        $this->generateIndexSitemap();
        $this->generateMaterialsSitemap();
        $this->generateConsumersSitemap();
    }

    /**
     * Cached safe column existence check.
     */
    private function hasColumn(string $table, string $column): bool
    {
        if (isset($this->columnCache[$table][$column])) {
            return $this->columnCache[$table][$column];
        }

        try {
            $exists = Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            Log::warning("Sitemap: schema check failed for {$table}.{$column} ({$e->getMessage()})");
            $exists = false;
        }

        return $this->columnCache[$table][$column] = $exists;
    }

    /**
     * Generate main sitemap index (sitemap.xml).
     */
    private function generateIndexSitemap(): void
    {
        $dom = $this->newDom("[Sitemap Generator] sitemap.xml updated at {$this->now}");
        $root = $dom->createElement('sitemapindex');
        $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($root);

        foreach ($this->conf as $key => $scope) {
            if ($key === 'index') continue;
            $root->appendChild($this->sitemapEntry($dom, $scope['url'], $this->now));
        }

        $this->saveDom($dom, $this->conf['index']['path']);
    }

    /**
     * Generate combined materials sitemap (static + dynamic).
     */
    private function generateMaterialsSitemap(): void
    {
        $data = [];
        $locales = array_values(config('app.available_locales', []));
        $types = config('app.materials.types', []);

        foreach ($types as $type => $conf) {
            if (empty($conf['sitemap'])) continue;

            $segment = trim($conf['urlSegment'] ?? '', '/');
            $table = $conf['tableName'] ?? null;

            // Root section
            $data[] = [
                'path' => $segment,
                'locales' => $locales,
                'lastmod' => $this->now,
            ];

            try {
                $qb = materialBuilder($type);
                $cols = ['id', 'updated_at'];

                if ($table) {
                    if ($this->hasColumn($table, 'alias')) $cols[] = 'alias';
                    if ($this->hasColumn($table, 'deleted_at')) $qb->whereNull('deleted_at');
                    if ($this->hasColumn($table, 'robots')) $cols[] = 'robots';
                    if ($this->hasColumn($table, 'robots')) {
                        $qb->whereNot('robots', 'none')->whereNot('robots', 'noindex');
                    }
                }

                $qb->select($cols)->with('contents:locale');

                foreach ($qb->cursor() as $i) {
                    $alias = $i->alias ? trim("/{$i->alias}", '/') : '';
                    $path = trim("{$segment}/{$alias}", '/');
                    $localesSorted = $this->sortedLocales($i->contents->pluck('locale')->toArray());
                    $data[] = [
                        'path' => $path,
                        'locales' => $localesSorted,
                        'lastmod' => $i->updated_at,
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning("Sitemap: materials[{$type}] failed: {$e->getMessage()}");
            }
        }

        $this->generateScopeSitemap('materials', $data);
    }

    /**
     * Generate consumers sitemap.
     */
    private function generateConsumersSitemap(): void
    {
        $data = [];
        $types = (array) config('app.consumers.types', []);
        $fallback = $this->appLocale;

        foreach ($types as $type => $conf) {
            if (empty($conf['sitemap'])) continue;

            $segment = trim($conf['urlSegment'] ?? $type, '/');
            $table = $conf['tableName'] ?? null;

            $data[] = [
                'path' => $segment,
                'locales' => [$fallback],
                'lastmod' => $this->now,
            ];

            try {
                $qb = consumerBuilder($type);
                $cols = ['id', 'updated_at'];
                if ($table && $this->hasColumn($table, 'alias')) $cols[] = 'alias';
                if ($table && $this->hasColumn($table, 'deleted_at')) $qb->whereNull('deleted_at');
                $qb->select($cols);

                foreach ($qb->cursor() as $i) {
                    $alias = $i->alias ? trim("/{$i->alias}", '/') : '';
                    $path = trim("{$segment}/{$alias}", '/');
                    $data[] = [
                        'path' => $path,
                        'locales' => [$fallback],
                        'lastmod' => $i->updated_at,
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning("Sitemap: consumers[{$type}] failed: {$e->getMessage()}");
            }
        }

        $this->generateScopeSitemap('consumers', $data);
    }

    /**
     * Generate sitemap file for a given scope.
     */
    private function generateScopeSitemap(string $scope, array $data): void
    {
        if ($scope === 'index' || !isset($this->conf[$scope])) return;

        $dom = $this->newDom("[Sitemap Generator] {$scope}.sitemap.xml updated at {$this->now}");
        $root = $dom->createElement('urlset');
        $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $root->setAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $dom->appendChild($root);

        foreach ($data as $row) {
            $url = $this->urlElement($dom, $row);
            $root->appendChild($url);
        }

        $this->saveDom($dom, $this->conf[$scope]['path']);
    }

    /**
     * Create a URL entry element.
     */
    private function urlElement(\DOMDocument $dom, array $i): \DOMElement
    {
        $url = $dom->createElement('url');
        $locales = $i['locales'] ?? [$this->appLocale];
        $path = trim($i['path'] ?? '', '/');
        $locPath = $path ? "/{$path}" : '';

        $loc = $dom->createElement('loc', rtrim("{$this->home}/{$locales[0]}{$locPath}", '/'));
        $url->appendChild($loc);

        if (count($locales) > 1) {
            foreach ($locales as $locale) {
                $link = $dom->createElement('xhtml:link');
                $link->setAttribute('rel', 'alternate');
                $link->setAttribute('hreflang', $locale);
                $link->setAttribute('href', rtrim("{$this->home}/{$locale}{$locPath}", '/'));
                $url->appendChild($link);
            }
        }

        $lastmod = $i['lastmod'] ?? $this->now;
        $lastmod = $lastmod instanceof Carbon ? $lastmod : Carbon::parse($lastmod);
        $url->appendChild($dom->createElement('lastmod', $lastmod->toAtomString()));

        return $url;
    }

    /**
     * Create a single <sitemap> entry for the index.
     */
    private function sitemapEntry(\DOMDocument $dom, string $loc, Carbon $lastmod): \DOMElement
    {
        $entry = $dom->createElement('sitemap');
        $entry->appendChild($dom->createElement('loc', $loc));
        $entry->appendChild($dom->createElement('lastmod', $lastmod->toAtomString()));
        return $entry;
    }

    /**
     * Helper to sort locales (default first).
     */
    private function sortedLocales(array $locales): array
    {
        if (in_array($this->appLocale, $locales, true)) {
            $locales = array_values(array_diff($locales, [$this->appLocale]));
            array_unshift($locales, $this->appLocale);
        }
        return $locales;
    }

    /**
     * Create a new DOMDocument with XML header and comment.
     */
    private function newDom(string $commentText): \DOMDocument
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->appendChild($dom->createComment("\n{$commentText}\n"));
        return $dom;
    }

    /**
     * Save DOMDocument to file.
     */
    private function saveDom(\DOMDocument $dom, string $path): void
    {
        $dom->formatOutput = true;
        $dom->save($path);
    }

    /** Get sitemap configuration. */
    public function getConf(): array
    {
        return $this->conf;
    }

    /** Get last sitemap update time. */
    public function lastUpdate(): string
    {
        $lastModified = File::lastModified($this->conf['index']['path']);
        return consumerDateTimeFormat($lastModified, 'admin', true);
    }
}
