<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class UpdateStaticMaterialViewController extends Controller
{
    /**
     * Get private page view of static material updating by type
     *
     * @param string|null $locale User locale
     * @param string $type Material type
     * @param string $content_locale Locale of material content
     * @return View
     */
    public function __invoke(
        ?string $locale,
        string $type,
        string $content_locale
    ): View
    {
        $this->authorize('permits', ['material', 'u']);

        $materialConf = config("app.materials.types.{$type}");
        abort_if(
            empty($materialConf) || !$materialConf['static'],
            403,
            "Type '{$type}' is not static material!");

        $material = materialBuilder($type)
            ->withWhereHas('contents', function (Builder $query) use ($content_locale) {
                $query->where(['locale' => $content_locale]);
            })->firstOrFail();
        $view = "admin.update-static-{$type}";

        return view($view,
            compact('material', 'type', 'content_locale')
        );
    }
}
