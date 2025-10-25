<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class CreateModeratorViewController extends Controller
{
    /**
     * Get private page view of moderator creating
     *
     * @param string|null $locale User locale
     * @return View
     * @throws AuthorizationException
     */
    public function __invoke(?string $locale): View
    {
        $this->authorize('permits', ['moderator', 'c']);

        return view('admin.create-moderator');
    }
}
