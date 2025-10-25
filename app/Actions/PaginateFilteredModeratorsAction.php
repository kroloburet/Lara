<?php

namespace App\Actions;

use App\Contracts\ActionContract;
use App\Models\Admin;
use App\Traits\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class PaginateFilteredModeratorsAction implements ActionContract
{
    use Action;

    /**
     * Get moderators list for filter paginator
     *
     * @param array $data
     * @return Collection
     */
    public function handle(array $data): Collection
    {
        // Fetch the moderator model.
        $moderators = Admin::withTrashed()->where('type', 'moderator');

        // Parse `order_by` using the trait
        [$orderCol, $orderDirection, $orderValue] =
            $this->parseOrderBy($data['order_by'] ?? null);

        // Apply filter if `orderValue` is present
        if ($orderValue !== null) {
            $moderators = $moderators->where($orderCol, $orderValue);
        }

        // With "query"
        if (!empty($data['query'])) {
            $moderators = $moderators->where(function ($builder) use ($data) {
                $query = $data['query'];
                $builder
                    ->where('email', 'like', "%$query%", 'or');
            });
        }

        // Return a result collection
        return $moderators
            ->limit($data['limit'] ?? config('app.settings.paginatorLimit'))
            ->orderBy($orderCol, $orderDirection)
            ->offset($data['offset'] ?? 0)
            ->get();
    }
}
