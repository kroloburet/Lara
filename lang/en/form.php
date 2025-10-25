<?php

return [
    "do_verify_new_email" => "Please verify your new email",
    "send_verify_notice" => "We have sent a verification email to the address you provided",
    "location" => [
        "label" => "Location",
        "hint" => "Enter your address in the field or place a marker on the map at your location. Alternatively, click the <i class='fa-solid fa-location-crosshairs'></i> icon and allow the System to detect your location automatically. To remove the set location and hide it from contacts, click the <i class='fa-solid fa-xmark'></i> icon.",
        "placeholder" => "Search for a place",
        "marker_title" => "Drag the marker or click on the desired location",
        "error" => "Unfortunately, we could not automatically determine your geolocation. Please try entering the address manually in the search field or placing the marker.",
        "get_current" => "Detect my current location",
        "reset" => "Remove location",
    ],
    "social_networks" => [
        "label" => "Social Networks",
        "hint" => "Select a social network from the list, enter the link, and click <q>+</q>.",
        "No_networks_to_add" => "You have added all social networks",
        "no_added" => "No social networks added",
    ],
    "links" => [
        "label" => "Links",
        "hint" => "Enter links to your website or other resources. Provide the text and URL, then click <q>+</q>.",
        "name_placeholder" => "My Website",
        "value_placeholder" => route('home'),
        "no_added" => "No links added",
    ],
    "emails" => [
        "label" => "Email Addresses",
        "hint" => "Enter one or more email addresses. Provide the label and email, then click <q>+</q>.",
        "name_placeholder" => "Main Inbox",
        "value_placeholder" => "example@mail.com",
        "no_added" => "No emails added",
    ],
    "phones" => [
        "label" => "Phone Numbers",
        "hint" => "Enter one or more phone numbers. Provide the label and number, then click <q>+</q>.",
        "name_placeholder" => "Cat Support",
        "value_placeholder" => "044 5555555",
        "no_added" => "No phone numbers added",
    ],
    "email" => [
        "label" => "Enter email",
        "hint" => "This email will be used for login and recovery",
        "placeholder" => "example@mail.com",
        "is_admin_notice" => "You are acting as Administrator! Email does not require verification.",
    ],
    "phone" => [
        "label" => "Enter phone number",
        "hint" => "Select your country code and enter your phone number with area or operator code. Only digits are allowed.",
        "placeholder" => "Phone number",
    ],
    "email_or_phone" => [
        "label" => "Enter your email or phone number",
        "hint" => "We will contact you using the provided email or phone number. Select your country code and enter your phone number with area or operator code. Only digits are allowed.",
    ],
    "password" => [
        "label" => "Enter password",
        "hint" => "The password must contain at least eight characters without spaces, including uppercase and lowercase letters, numbers, and special characters. Click the <i class='fa-solid fa-arrows-rotate'></i> icon to generate a strong password.",
        "repeat_label" => "Repeat password",
        "new_label" => "Enter new password",
    ],
    "order_by" => [
        "newest" => "Newest first",
        "oldest" => "Oldest first",
        "blocked" => "Blocked",
        "soft_deletes" => "Hidden first",
    ],
    "robots" => [
        "all" => "Index without restrictions",
        "noindex" => "Do not show material in search results",
        "nofollow" => "Do not follow links in the material",
        "noimageindex" => "Do not index images in the material",
        "none" => "Do not index at all",
    ],
    "permissions" => [
        "All_deny" => "No permissions",
        "r" => "View",
        "c" => "Create",
        "u" => "Edit",
        "d" => "Delete",
        "menu" => [
            "label" => "Menu",
            "hint" => "Define this Moderator's permissions within the Admin Panel for managing the menu",
        ],
        "material" => [
            "label" => "Materials",
            "hint" => "Define this Moderator's permissions within the Admin Panel for managing materials",
        ],
        "moderator" => [
            "label" => "Moderators",
            "hint" => "Define this Moderator's permissions within the Admin Panel for managing other Moderators",
            "notice" => "This setting is potentially dangerous",
        ],
    ],
    "sitemap" => [
        "label" => "Update condition",
        "hint" => "Under what condition should the Sitemap be updated. Update automatically every time a material or Profile is added or deleted. Update only manually from this panel.",
        "auto" => "Automatically",
        "manually" => "Manually",
    ],
    "appeal" => [
        "theme" => [
            "label" => "Specify the subject of your inquiry",
            "important" => "Urgent / Important",
            "collaboration" => "Collaboration",
            "complaint" => "Complaint",
            "bug" => "Found a bug",
            "donate" => "Donation",
            "appeal" => "Inquiry",
            "question" => "Question",
            "other" => "Other",
        ],
        "message" => [
            "label" => "Enter your message"
        ],
        "ok" => "<h5>Done!</h5>Thank you, your inquiry has been sent!",
        "popup_title" => "Feedback",
        "popup_desc" => "Provide your contact details for a response. Select the subject of your inquiry and add your message.",
    ],
];
