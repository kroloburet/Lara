<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EmailVerifyDenyViewController extends Controller
{
    /**
     * Get view of email verify deny page
     *
     * @param string|null $locale User locale
     * @return View|RedirectResponse
     */
    public function __invoke(?string $locale): View|RedirectResponse
    {
        $currentConsumer = auth()->user();

        if ($currentConsumer && $currentConsumer->isVerifiedEmail()) {
            return redirect()->route('verify.verify-email-success');
        }

        return view('verify.verify-email-deny');
    }
}
