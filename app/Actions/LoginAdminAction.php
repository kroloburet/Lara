<?php

namespace App\Actions;

use App\Contracts\ActionContract;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class LoginAdminAction implements ActionContract
{
    /**
     * Handle login the administrator or moderator
     *
     * @param array $data
     * @return array
     */
    public function handle(array $data): array
    {
        $responseData = [
            'message' => __('auth.not_exist'),
            'redirect' => null,
        ];

        // Admin is blocked
        $admin = Admin::onlyTrashed()->firstWhere('email', $data['email']);
        if (! empty($admin)) {
            $responseData['message'] = __('auth.blocked_admin');
            return $responseData;
        }

        // Set credentials
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        // Check and login
        $guard = Auth::guard('admin');
        if ($guard->attempt($credentials, $data['remember'] ?? false)) {

            $guard->login($guard->user(), $data['remember'] ?? false);

            $responseData['message'] = null;
            $responseData['redirect'] = route('admin.dashboard');

            // Push log event
            $guard->user()->pushLogEvent("Authenticated in the System");
        }

        // Return response data
        return $responseData;
    }
}
