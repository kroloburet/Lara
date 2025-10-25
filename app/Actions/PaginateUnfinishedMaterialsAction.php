<?php

namespace App\Actions;

use App\Contracts\ActionContract;
use Illuminate\Database\Eloquent\Collection;

class PaginateUnfinishedMaterialsAction implements ActionContract
{
    /**
     * Get unfinished materials list for paginator
     *
     * @param array $data
     * @return Collection
     */
    public function handle(array $data): Collection
    {
        $materials = materialBuilder($data['type']);
        $locales = config('app.available_locales');

        return $materials
            ->where(fn($query) => collect($locales)
                ->each(fn($locale) => $query->orWhereDoesntHave('contents',
                    fn($subQuery) => $subQuery->where('locale', $locale)
                ))
            )
            ->latest()
            ->limit($data['limit'] ?? config('app.settings.paginatorLimit'))
            ->offset($data['offset'] ?? 0)
            ->get();
    }
}
