<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menu';
    protected $fillable = [
        'title',
        'parent_id',
        'url',
        'order',
        'target',
        'locale',
    ];

    protected static function booted()
    {
        static::saving(function ($menu) {
            // Ensure that the order >= 1
            if ($menu->order < 1) {
                $menu->order = 1;
            }
        });
    }

    public function parent()
    {
        $belongsTo = $this->belongsTo(Menu::class, 'parent_id');
        return isAdminCheck() ? $belongsTo->withTrashed() : $belongsTo;
    }

    public function children()
    {
        $hasMany = $this->hasMany(Menu::class, 'parent_id')
            ->orderBy('order');
        return isAdminCheck() ? $hasMany->withTrashed() : $hasMany;
    }

    public function allChildren()
    {
        $hasMany = $this->hasMany(Menu::class, 'parent_id')
            ->with('allChildren')
            ->orderBy('order');
        return isAdminCheck() ? $hasMany->withTrashed() : $hasMany;
    }
}
