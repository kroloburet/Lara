<?php

namespace App\Traits\Models\Material;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Categorizable
{
    public const CATEGORY_FILLABLE = ['category_id'];

    public function category(): BelongsTo
    {
        $belongsTo = $this->belongsTo(Category::class);

        if (isAdminCheck()) {
            return $belongsTo->withTrashed();
        }

        return $belongsTo;
    }
}
