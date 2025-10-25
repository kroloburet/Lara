<?php

return [
    "login" => [
        "page" => [
            "admin" => [
                "meta_title" => "Administrator Login | " . env("APP_NAME"),
                "meta_desc" => "Administrator Login | " . env("APP_NAME"),
                "page_title" => "Administrator Login",
                "dim" => "Log in to manage the System. Lost access? <a class='toggleRecoveryLoginForm'>Recover access</a>",
            ],
            "remember_label" => "Remember me on this device",
            "recovery_access_header" => "Access Recovery",
            "recovery_access_desc" => "If you have lost your login details, enter your email. The System will send an email with recovery instructions.",
        ],
    ],
    "not_exist" => "<h5>User not found</h5>Please check your credentials or <a class='toggleRecoveryLoginForm'>recover access</a> if you are registered and have lost your login details.",
    "blocked_admin" => "<h5>Access denied</h5>Your access to the admin panel is restricted. Please contact the main Administrator.",
    "recovery_send_error" => "<h5>Error</h5>Unfortunately, the email could not be sent to :email. Please try again or <a class='appeal'>contact support</a>.",
    "recovery_ok_msg" => "<h5>Done!</h5>An email with instructions has been sent to :email. The message may appear in the <q>Spam</q> folder.",
    "password" => "You entered an incorrect password.",
    "throttle" => "Too many login attempts. For security reasons, please try again in :seconds seconds.",
];
