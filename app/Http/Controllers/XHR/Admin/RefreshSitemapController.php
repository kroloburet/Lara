<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;
use Illuminate\Auth\Access\AuthorizationException;

class RefreshSitemapController extends Controller
{
    /**
     * Handle of refresh the sitemap.xml and other sitemaps
     *
     * @param SitemapService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function refresh(SitemapService $service)
    {
        $this->authorize('superAdmin');

        $service->writeSitemap();

        return response()->json([
            'ok' => true,
            'message' => __('settings.sitemap.refresh_done'),
            'lastUpdate' => $service->lastUpdate(),
        ]);
    }

    /**
     * Get the contents of sitemap files
     *
     * @param SitemapService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function view(SitemapService $service)
    {
        $this->authorize('superAdmin');

        $sitemapConf = $service->getConf();
        $sitemapData = null;

        collect($sitemapConf)->each(function($file, $key) use (&$sitemapData) {
            $content = file_get_contents($file['path']);
            if (empty($content)) {
                $sitemapData = null;
                return false;
            }
            return $sitemapData[$key] = $content;
        });

        return response()->json([
            'sitemapData' => $sitemapData,
        ]);
    }
}
