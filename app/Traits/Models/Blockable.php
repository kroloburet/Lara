<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

trait Blockable
{
    use SoftDeletes;

    public function block()
    {
        if ($this->isBlocked()) {
            return true;
        }

        return (bool) $this->delete();
    }

    public function unblock()
    {
        if (!$this->isBlocked()) {
            return true;
        }

        return $this->restore();
    }

    public function isBlocked(): bool
    {
        return $this->trashed();
    }
}
