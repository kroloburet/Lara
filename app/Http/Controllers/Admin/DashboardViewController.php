<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Contracts\View\View;

class DashboardViewController extends Controller
{
    /**
     * Get private page view of administrator panel
     *
     * @param string|null $locale User locale
     * @return View
     */
    public function __invoke(?string $locale): View
    {
        $moderators_count = Admin::withTrashed()->where('type', 'moderator')->count();
        $systemLog = $this->getMergedAdminLogs();

        return view('admin.dashboard', compact('moderators_count', 'systemLog'));
    }

    /**
     * Administrator only.
     * Collects and merges logs from all Admin users,
     * adding consumerName to each log entry.
     *
     * @return array
     */
    protected function getMergedAdminLogs(): array
    {
        if (! isAdminCheck('admin')) return [];

        return Admin::query()
            ->withTrashed()
            ->select(['id', 'email', 'log'])
            ->get()
            ->flatMap(function (Admin $consumer) {
                $logs = $consumer->log ?? [];

                return collect($logs)->map(function ($log) use ($consumer) {
                    return [
                        'consumerName' => $consumer->name,
                        'timestamp' => $log['timestamp'],
                        'event' => $log['event'],
                    ];
                });
            })
            ->values()
            ->toArray();
    }
}
