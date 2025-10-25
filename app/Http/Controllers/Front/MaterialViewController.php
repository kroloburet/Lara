<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class MaterialViewController extends Controller
{
    /**
     * Get material model with content and return view
     *
     * @param string $type Type of material
     * @param string $alias Material alias
     * @param string $locale Locale of material content
     * @return View
     */
    protected function getMaterial(
        string $type,
        string $alias,
        string $locale
    ): View
    {
        $material = materialBuilder($type)
            ->where('alias', $alias)
            ->with(['contents' => function ($query) use ($locale) {
                $query->where('locale', $locale)->first();
            }])->first();

        abort_if(empty($material) || empty($material->content()), 404);

        // Get layout settings
        $layoutSettings = materialLayoutSettings($material->type, $material);

        // Incrementing views
        statistic($material)->incrementKey('views');

        return view('front.material', compact('material', 'layoutSettings'));
    }

    /**
     * Get public page view of category
     *
     * @param string $locale User locale
     * @param string $alias Material alias
     * @return View
     */
    public function category(string $locale, string $alias): View
    {
        return $this->getMaterial('category', $alias, $locale);
    }

    /**
     * Get public page view of page
     *
     * @param string $locale User locale
     * @param string $alias Material alias
     * @return View
     */
    public function page(string $locale, string $alias): View
    {
        return $this->getMaterial('page', $alias, $locale);
    }
}
