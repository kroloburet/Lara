<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class StaticMaterialViewController extends Controller
{
    /**
     * Get static material model with content and return view
     *
     * @param string $type Type of material
     * @param string $locale Locale of material content
     * @return View
     */
    protected function getMaterial(
        string $type,
        string $locale
    ): View
    {
        $material = materialBuilder($type)
            ->with(['contents' => function ($query) use ($locale) {
                $query->where('locale', $locale)->first();
            }])->first();

        abort_if(empty($material) || empty($material->content()), 404);

        // Get layout settings
        $layoutSettings = materialLayoutSettings($material->type, $material);

        return view("front.static-{$type}", compact('material', 'layoutSettings'));
    }

    /**
     * Get home public page
     *
     * @param string $locale User locale
     * @return View
     */
    public function home(string $locale): View
    {
        return $this->getMaterial('home', $locale);
    }

    /**
     * Get contact public page
     *
     * @param string $locale User locale
     * @return View
     */
    public function contact(string $locale): View
    {
        return $this->getMaterial('contact', $locale);
    }
}
