<?php

namespace App\Actions;

use App\Contracts\ActionContract;
use App\Traits\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class PaginateFilteredMaterialsAction implements ActionContract
{
    use Action;

    /**
     * Get materials list for filter paginator
     *
     * @param array $data
     * @return Collection
     */
    public function handle(array $data): Collection
    {
        // Fetch the base material model.
        $materials = materialBuilder($data['type']);

        // Parse `order_by` using the trait
        [$orderCol, $orderDirection, $orderValue] =
            $this->parseOrderBy($data['order_by'] ?? null);

        // Apply filter if `orderValue` is present
        if ($orderValue !== null) {
            $materials = $materials->where($orderCol, $orderValue);
        }

        // Sub materials request
        if (!empty($data['category_id'])) {
            $materials = $materials->where('category_id', $data['category_id']);
        }

        // Return a result collection
        return $materials
            // Only with all lang versions
            ->where(fn($query) => collect(config('app.available_locales'))
                ->each(fn($locale) => $query->whereHas('contents',
                    fn($subQuery) => $subQuery->where('locale', $locale)
                ))
            )
            // Only has lang version and find query string
            ->withWhereHas('contents', function ($builder) use ($data) {
                $builder->where(['locale' => $data['locale'] ?? app()->getLocale()]);

                if (!empty($data['query'])) {
                    $query = $data['query'];

                    $builder->whereRaw(
                        'MATCH (title, description, content) AGAINST (? IN BOOLEAN MODE)',
                        ["$query*"]
                    );
                }
            })
            ->limit($data['limit'] ?? config('app.settings.paginatorLimit'))
            ->orderBy($orderCol, $orderDirection)
            ->offset($data['offset'] ?? 0)
            ->get();
    }
}
