<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CreateMaterialViewController extends Controller
{
    /**
     * Get private page view of material creating
     *
     * @param string|null $locale User locale
     * @param string $type Type of material
     * @return View
     */
    public function __invoke(?string $locale, string $type, string $content_locale): View
    {
        abort_if(! key_exists($type, config('app.materials.types')), 404);

        $this->authorize('permits', ['material', 'c']);

        return view('admin.create-material',
            compact('type', 'content_locale')
        );
    }
}
