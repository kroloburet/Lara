<?php

namespace App\Http\Controllers\XHR;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use Illuminate\Http\Request;

class BugReportController extends Controller
{
    /**
     * Handle user bug report.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:6'],
            'text' => ['nullable', 'string', 'max:255'],
        ]);

        BugReport::create([
            'url' => getUrlPath($request),
            'method' => $request->method(),
            'status' => $validated['code'],
            'status_text' => "User Report: {$validated['text']}",
            'server_header' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'user_agent' => $request->header('User-Agent'),
            'ip' => $request->header('X-Forwarded-For', $request->ip()),
            'page_url' => $request->header('referer') ? parse_url($request->header('referer'), PHP_URL_PATH) . ($request->header('referer') ? '?' . parse_url($request->header('referer'), PHP_URL_QUERY) : '') : $this->getUrlPath($request),
        ]);

        return response()->json(['ok' => true]);
    }
}
