<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class UpdateMaterialViewController extends Controller
{
    /**
     * Get private page view of material updating
     *
     * @param string|null $locale User locale
     * @param string $type Type of material
     * @param string $alias Material alias
     * @param string $content_locale Locale of material content
     * @return View
     */
    public function __invoke(
        ?string $locale,
        string $type,
        string $alias,
        string $content_locale
    ): View
    {
        abort_if(! key_exists($type, config('app.materials.types')), 404);

        $this->authorize('permits', ['material', 'u']);

        $material = materialBuilder($type)
            ->where('alias', $alias)
            ->withWhereHas('contents', function (Builder $query) use ($content_locale) {
                $query->where(['locale' => $content_locale]);
            })
            ->firstOrFail();

        return view('admin.update-material',
            compact('material', 'type', 'content_locale')
        );
    }
}
