<?php

namespace App\Exceptions;

use App\Models\BugReport;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * This intercepts the bugs with the codes specified in the configuration
 * of app.bug_report_status_codes, writes in the bug_reports table.
 * Reports and Managing Reports through bug-reports-list-component
 */
class BugReportHandler
{
    public function report(Throwable $exception): void
    {
        $statusCode = $this->getStatusCode($exception);
        if (in_array($statusCode, config('app.bug_report_status_codes', []))) {
            $request = request();
            $errorDetails = $this->getErrorDetails($exception);

            $exists = BugReport::where('status', $statusCode)
                ->where('file', $errorDetails['file'])
                ->where('line', $errorDetails['line'])
                ->exists();

            if (!$exists) {
                BugReport::create([
                    'url' => $this->getUrlPath($request),
                    'method' => $request->method(),
                    'status' => $statusCode,
                    'status_text' => 'System Report: ' . $exception->getMessage() ?: 'Unknown error',
                    'server_header' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                    'user_agent' => $request->header('User-Agent'),
                    'ip' => $request->header('X-Forwarded-For', $request->ip()),
                    'page_url' => $this->getUrlPath($request),
                    'file' => $errorDetails['file'],
                    'line' => $errorDetails['line'],
                    'stack_trace' => $exception->getTraceAsString(),
                ]);
            }
        }
    }

    public function render(Request $request, Throwable $exception)
    {
        if (app()->environment('production') || !config('app.debug')) {
            if ($exception instanceof HttpException) {
                $statusCode = $exception->getStatusCode();
                return response()->view("errors.{$statusCode}", [], $statusCode);
            }
        }

        return null; // To hand over processing to the parent handler
    }

    protected function getStatusCode(Throwable $exception): int
    {
        if ($exception instanceof HttpException) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    protected function getErrorDetails(Throwable $exception): array
    {
        $trace = $exception->getTrace();
        foreach ($trace as $frame) {
            if (isset($frame['file']) && !str_contains($frame['file'], '/vendor/')) {
                return [
                    'file' => $frame['file'],
                    'line' => $frame['line'] ?? null,
                ];
            }
        }

        return [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }

    protected function getUrlPath($request): string
    {
        return $request->fullUrl();
    }
}
