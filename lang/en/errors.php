<?php

return [
    "default" => "<h5>Something went wrong</h5>Problem: <i class='red-text'>:error</i>.<br>Our team is already working on resolving it. <a class='bugReport' data-text=':error'>Report details</a> about this error to help us fix it faster.",
    "err_and_try" => "<span class='err-text'>An error occurred.</span> Please try again.",
    "xhr" => [
        "401" => "<h5>Your session has expired</h5>To continue, please <a href='/admin/login'>log in</a>.",
        "402" => "<h5>Payment required</h5>Unfortunately, we can only process your request after receiving payment.",
        "403" => "<h5>Access forbidden</h5>You do not have sufficient permissions to perform this action. Try <a onclick='redirect()'>refreshing the page</a>.",
        "404" => "<h5>Action not available</h5>No actions were found for your request.",
        "406" => "<h5>Unacceptable format</h5>Your request was processed, but we cannot provide a response in the requested format.",
        "412" => "<h5>Request rejected</h5>Unfortunately, we cannot process your request due to missing required data. Try <a onclick='redirect()'>refreshing the page</a>.",
        "419" => "<h5>Page expired</h5>Please <a onclick='redirect()'>refresh the page</a> and try again.",
        "429" => "<h5>Too many requests</h5>You are sending too many requests. For security reasons, this feature is temporarily limited. Please try again later.",
        "503" => "<h5>Service temporarily unavailable</h5>A technical issue occurred on our side. <a class='bugReport' data-text='Service is not available' data-code='503'>Report details</a> to help us fix it faster.",
    ],
    "401" => [
        "meta_title" => "Authorization Required | " . env('APP_NAME'),
        "meta_desc" => "Please log in to access this page.",
        "page_title" => "You are not logged in",
        "page_desc" => "<p>Authorization is required to access this page. Please <a href='/admin/login'>log in</a> or <a onclick='redirect()'>refresh the page</a> and try again.</p>",
    ],
    "402" => [
        "meta_title" => "Payment Required | " . env('APP_NAME'),
        "meta_desc" => "This action or page will become available after successful payment.",
        "page_title" => "Payment Required",
        "page_desc" => "<p>Unfortunately, we can only process your request after receiving payment.</p>",
    ],
    "403" => [
        "meta_title" => "Access Forbidden | " . env('APP_NAME'),
        "meta_desc" => "You do not have sufficient permissions to view this page.",
        "page_title" => "Access Forbidden",
        "page_desc" => "<p>You do not have sufficient permissions to view this page.</p>",
    ],
    "404" => [
        "meta_title" => "Page Not Found | " . env('APP_NAME'),
        "meta_desc" => "The page you are looking for does not exist or has been moved.",
        "page_title" => "Page Does Not Exist",
        "page_desc" => "<p>It seems the page you are looking for has been deleted or never existed. Don’t worry — you can return to the <a href='/'>home page</a> or browse other sections of the site.</p>",
    ],
    "419" => [
        "meta_title" => "Page Expired | " . env('APP_NAME'),
        "meta_desc" => "The page has expired. Please refresh it and try again.",
        "page_title" => "Page Expired",
        "page_desc" => "<p>For security reasons, this page has expired. Please <a onclick='redirect()'>refresh it</a> and try again.</p>",
    ],
    "429" => [
        "meta_title" => "Too Many Requests | " . env('APP_NAME'),
        "meta_desc" => "You are sending too many requests. The feature is temporarily limited.",
        "page_title" => "Too Many Requests",
        "page_desc" => "<p>You are making too many requests to our server. For security reasons, this feature is temporarily limited. Please wait and try again later.</p>",
    ],
    "500" => [
        "meta_title" => "Internal Server Error | " . env('APP_NAME'),
        "meta_desc" => "An unexpected error occurred on our server.",
        "page_title" => "Something Went Wrong",
        "page_desc" => "<p>An internal server error occurred. We are already working on fixing it. Try <a onclick='redirect()'>refreshing the page</a> or <a class='bugReport' data-text='Server error' data-code='500'>report details</a> to help us resolve it faster.</p>",
    ],
    "503" => [
        "meta_title" => "Service Temporarily Unavailable | " . env('APP_NAME'),
        "meta_desc" => "The service is temporarily unavailable due to maintenance, overload, or other reasons.",
        "page_title" => "Service Temporarily Unavailable",
        "page_desc" => "<p>One of our services is not responding. We are working on restoring it. Please try <a onclick='redirect()'>refreshing the page</a> a bit later. <a class='bugReport' data-text='Service is not available' data-code='503'>Report details</a> to help us fix this issue faster.</p>",
    ],
];
