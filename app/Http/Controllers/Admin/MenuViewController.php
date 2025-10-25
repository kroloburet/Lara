<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class MenuViewController extends Controller
{
    /**
     * Get private page view of menu management
     *
     * @param string|null $locale User locale
     * @return View
     * @throws AuthorizationException
     */
    public function __invoke(?string $locale): View
    {
        $this->authorize('permits', ['menu', 'r']);

        $locale = app()->getLocale();
        $menu = getMenu($locale, true);

        return view('admin.menu', compact('menu', 'locale'));
    }
}
