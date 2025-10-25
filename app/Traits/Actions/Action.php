<?php

namespace App\Traits\Actions;

trait Action
{
    /**
     * Parse and prepare data from the `order_by` parameter.
     *
     * @param string|null $orderBy
     * @return array [orderColumn, orderDirection, orderValue]
     */
    public function parseOrderBy(?string $orderBy): array
    {
        // Default sorting
        $defaultColumn = 'id';
        $defaultDirection = 'desc';

        // Return default values if `order_by` is not provided
        if (!$orderBy) {
            return [$defaultColumn, $defaultDirection, null];
        }

        // Split `order_by` into column and direction/value
        [$orderColumn, $orderExtra] = explode('__', $orderBy) + [1 => $defaultDirection];

        // If `orderExtra` is not a valid sorting direction, treat it as a value
        if (!in_array($orderExtra, ['asc', 'desc'])) {
            return [$orderColumn, $defaultDirection, $orderExtra];
        }

        return [$orderColumn, $orderExtra, null];
    }
}
