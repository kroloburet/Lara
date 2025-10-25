<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $fillable = [
        'url',
        'method',
        'status',
        'status_text',
        'server_header',
        'user_agent',
        'ip',
        'page_url',
        'file',
        'line',
        'stack_trace',
    ];
}
