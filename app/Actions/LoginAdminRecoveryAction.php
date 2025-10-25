<?php

namespace App\Actions;

use App\Contracts\ActionContract;
use App\Models\Admin;
use App\Services\RecoveryService;

class LoginAdminRecoveryAction implements ActionContract
{
    /**
     * Handle recovery Admin login data
     *
     * @param array $data
     * @return array
     */
    public function handle(array $data): array
    {
        $recoveryService = app(RecoveryService::class);
        $responseData = [
            'status' => 'error',
            'message' => __('auth.not_exist'),
        ];

        $admin = Admin::firstWhere('email', $data['email']);

        // Error if admin not exist
        if (empty($admin)) {
            return $responseData;
        }

        // Send recovery notice
        if (! $recoveryService->sendRecoveryLoginNotice($admin)) {
            $responseData['message'] = __('auth.recovery_send_error', ['email' => $data['email']]);
        } else {
            $responseData['status'] = 'ok';
            $responseData['message'] = __('auth.recovery_ok_msg', ['email' => $data['email']]);

            // Push log event
            $admin->pushLogEvent("A request to restore access has been sent");
        }

        // Return response data
        return $responseData;
    }
}
