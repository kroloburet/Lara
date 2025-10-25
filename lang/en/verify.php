<?php

return [
    "email" => [
        "resend_response_ok" => "<h5>Done!</h5>The email may appear in the <q>Spam</q> folder. Please check it.",
        "resend_response_err" => "<h5>An error occurred</h5>Unfortunately, the email could not be sent. Please try again or <a class='appeal'>contact support</a>.",
        "success_page" => [
            "meta_title" => "Email Verified | " . env("APP_NAME"),
            "meta_desc" => "Email Verified | " . env("APP_NAME"),
            "page_title" => "Email Verified",
            "subtitle" => "You have verified your email. <a href='" . route("admin.login") . "'>Log in</a>.",
        ],
        "deny_page" => [
            "meta_title" => "Verify Email | " . env("APP_NAME"),
            "meta_desc" => "Verify Email | " . env("APP_NAME"),
            "page_title" => "Verify Email",
            "subtitle" => "A verification email has been sent to <q>:email</q>. Please follow the link in the email to continue.",
            "lost_notice" => "Didn’t receive or lost the email?",
            "resend" => "Resend email",
        ],
        "abort_page" => [
            "meta_title" => "Verification Expired | " . env("APP_NAME"),
            "meta_desc" => "Verification Expired | " . env("APP_NAME"),
            "page_title" => "Verification Expired",
            "subtitle" => "This verification link is no longer valid. Your email has already been verified, or your account has been deleted.",
        ],
    ],
];
