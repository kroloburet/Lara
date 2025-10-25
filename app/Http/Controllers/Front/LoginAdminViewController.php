<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LoginAdminViewController extends Controller
{
    /**
     * Get public page view of admin login
     *
     * @param string|null $locale User locale
     * @return View|RedirectResponse
     */
    public function __invoke(?string $locale): View|RedirectResponse
    {
        if (isAdminCheck()) {
            return redirect()->route('admin.dashboard');
        }

        return view('front.login');
    }
}
