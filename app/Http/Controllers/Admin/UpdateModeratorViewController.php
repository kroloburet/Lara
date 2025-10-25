<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class UpdateModeratorViewController extends Controller
{
    /**
     * Get private page view of moderator updating
     *
     * @param string|null $locale User locale
     * @return View
     * @throws AuthorizationException
     */
    public function __invoke(?string $locale, int $id): View
    {
        $this->authorize('permits', ['moderator', 'u']);

        $moderator = Admin::withTrashed()->where('type', 'moderator')->findOrFail($id);

        return view('admin.update-moderator', compact('moderator'));
    }
}
