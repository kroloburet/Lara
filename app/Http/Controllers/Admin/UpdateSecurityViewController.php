<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UpdateSecurityViewController extends Controller
{
    /**
     * Get private page view of security updating
     *
     * @param string|null $locale User locale
     * @return View
     */
    public function __invoke(?string $locale): View
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.update-security', compact('admin'));
    }
}
