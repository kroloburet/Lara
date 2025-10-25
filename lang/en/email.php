<?php

$appContact = route('contact');
$currentYear = date('Y');

return [
    "title" => "Content Management System",
    "footer" => "Copyright © $currentYear All rights Reserved & Stay With Ukraine!<br><a href='$appContact' style='text-decoration: none;'>Feedback & Support</a>",
    "recovery" => [
        "subject" => "Access Recovery",
        "body" => "<p>A request has been made to recover access to your Account. Please note that the recovery link is one-time use. If you did not make this request, ignore this email. Your data remains secure. Never share this message with anyone.<br><a href=':signedUrl' target='_blank'>Change access credentials</a></p>",
    ],
    "verify_email" => [
        "subject" => "Verify Email",
        "body" => "<p>Please click the link below to verify your email address. Note that the verification link is one-time use. If you received this message in error, please ignore it. Never share this message with anyone.<br><a href=':signedUrl' target='_blank'>Verify email</a></p>",
    ],
    "complain" => [
        "subject" => "Complaint",
        "body" => "<p>Email: :email<br>Phone: :phone<br>Resource: <a href=':url' target='_blank'>:url</a><br>Subject: <q>:theme</q><br>Message: <q>:message</q></p>",
    ],
    "appeal" => [
        "subject" => "Inquiry",
        "body" => "<p>Email: :email<br>Phone: :phone<br>Subject: <q>:theme</q><br>Message: <q>:message</q></p>",
    ],
];
