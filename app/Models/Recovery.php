<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Recovery extends Model
{
    protected $fillable = [
        'token',
        'email',
        'consumer_type',
        'consumer_id',
        'created_at',
    ];

    protected $hidden = [
        'token',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function consumer(): MorphTo
    {
        return $this->morphTo();
    }
}
