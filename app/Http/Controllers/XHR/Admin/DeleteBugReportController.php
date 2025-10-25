<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteBugReportController extends Controller
{
    /**
     * Handle of bug report deleted
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $result = BugReport::query()->findOrFail($id)->deleteOrFail();

        return response()->json(['ok' => $result]);
    }
}
