<?php

namespace App\Http\Controllers\XHR;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PulseController extends Controller
{
    /**
     * This is used to validate the Consumer session and synchronize
     * some data between the server and the client in real time.
     * First of all it checks the client session.
     * This control previously open windows and browser
     * tabs with private Consumer pages. All open windows and tabs
     * with private pages must be reloaded if the Consumer is logout of the
     * system or re-logged in.
     *
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(): JsonResponse
    {
        $subscriber = request()->get('subscriber'); // admin
        $this->validateSubscriber($subscriber);

        $checkMethod = "{$subscriber}Check";
        $status = "[Pulse] Check session for {$subscriber}... ";

        abort_if(
            ! $this->$checkMethod(),
            401,
            "$status Failed!"
        );

        return response()->json([
            'status' => "$status Ok!",
            // ... another data for global pulse() js function
        ]);
    }

    /**
     * The logic of validation the subscriber type
     *
     * @param string $subscriber
     * @return void
     */
    private function validateSubscriber(string $subscriber): void
    {
        $allowableSubscribers = ['admin'];

        abort_if(
            ! in_array($subscriber, $allowableSubscribers),
            401,
            "[Pulse] Subscriber {$subscriber} is not allowed!"
        );
    }

    /**
     * The logic of verification for the administrator
     *
     * @return bool
     */
    private function adminCheck(): bool
    {
        return auth('admin')->check();
    }
}

