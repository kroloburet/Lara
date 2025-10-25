<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class ModeratorsViewController extends Controller
{
    /**
     * Get private page view of moderators list
     *
     * @param string|null $locale User locale
     * @return View
     * @throws AuthorizationException
     */
    public function __invoke(?string $locale): View
    {
        $this->authorize('permits', ['moderator', 'r']);

        return view('admin.moderators');
    }
}
