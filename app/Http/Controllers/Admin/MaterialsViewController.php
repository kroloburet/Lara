<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class MaterialsViewController extends Controller
{
    /**
     * Get private page view of materials list
     *
     * @param string|null $locale User locale
     * @param string $type Type of material
     * @return View
     */
    public function __invoke(?string $locale, string $type): View
    {
        abort_if(! key_exists($type, config('app.materials.types')), 404);

        return view('admin.materials',
            compact('type')
        );
    }
}
