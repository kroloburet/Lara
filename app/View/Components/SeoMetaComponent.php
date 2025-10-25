<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class SeoMetaComponent extends Component
{
    private array $locales;
    private ?string $defaultLocale;

    private ?string $secondUrlSegment;
    private ?string $alias;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Model|null $resource
    ) {
        $this->locales = array_values(config('app.available_locales'));
        $this->defaultLocale = config('app.fallback_locale');
        $this->secondUrlSegment = request()->segment(2);
        $this->alias = $this->resource?->alias ? "/{$this->resource->alias}" : "";
    }

    /**
     * Check if the second segment of the URL among registered "urlSegment"
     * - See: app.materials.types config, app.consumers.types config
     *
     * @return bool
     */
    private function isSegmentRegistered(): bool
    {
        $resourceSettings = array_merge(
            config('app.materials.types', []),
            config('app.consumers.types', []),
        );

        return collect($resourceSettings)
            ->pluck('urlSegment')
            ->contains($this->secondUrlSegment);
    }

    /**
     * Generate alternate links.
     */
    private function generateAlternateLinks(): array
    {
        if (! $this->isSegmentRegistered()) {
            return [];
        }

        $links = [];

        foreach ($this->locales as $locale) {
            $localeLang = $this->defaultLocale === $locale ? 'x-default' : $locale;
            $links[] = [
                'hreflang' => $localeLang,
                'href' => url("{$locale}/{$this->secondUrlSegment}{$this->alias}"),
            ];
        }

        return $links;
    }

    /**
     * Generate canonical link.
     */
    private function generateCanonicalLink(): array
    {
        if (! $this->isSegmentRegistered()) {
            return [];
        }

        return ['href' => url("{$this->defaultLocale}/{$this->secondUrlSegment}{$this->alias}")];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        $alternateLinks = $this->generateAlternateLinks();
        $canonicalLink = $this->generateCanonicalLink();

        if (
            empty($alternateLinks) &&
            empty($canonicalLink)
        ) return null;

        return view('components.seo-meta-component',
            compact(
            'alternateLinks',
            'canonicalLink',
        ));
    }
}
