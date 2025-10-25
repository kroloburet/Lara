<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaginateBugReportsListController extends Controller
{
    /**
     * Get HTML data for bug reports paginator
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $reports = BugReport::query()
            ->latest()
            ->limit($request->get('limit') ?? config('app.settings.paginatorLimit'))
            ->offset($request->get('offset') ?? 0)
            ->get();

        return response()->json([
            'html' => view(
                'components.layouts.admin.bug-reports-list',
                compact('reports')
            )->render()
        ]);
    }
}
