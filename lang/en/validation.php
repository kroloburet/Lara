<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    "accepted" => "The :attribute field must be accepted.",
    "accepted_if" => "The :attribute field must be accepted when :other is :value.",
    "active_url" => "The :attribute field must be a valid URL.",
    "after" => "The :attribute field must be a date after :date.",
    "after_or_equal" => "The :attribute field must be a date equal to or after :date.",
    "alpha" => "The :attribute field may only contain letters.",
    "alpha_dash" => "The :attribute field may only contain letters, numbers, dashes, and underscores.",
    "alpha_num" => "The :attribute field may only contain letters and numbers.",
    "array" => "The :attribute field must be an array.",
    "ascii" => "The :attribute field may only contain single-byte alphanumeric characters and symbols.",
    "before" => "The :attribute field must be a date before :date.",
    "before_or_equal" => "The :attribute field must be a date before or equal to :date.",
    "between" => [
        "array" => "The :attribute field must have between :min and :max items.",
        "file" => "The :attribute field must be between :min and :max kilobytes.",
        "numeric" => "The :attribute field must be between :min and :max.",
        "string" => "The :attribute field must be between :min and :max characters.",
    ],
    "boolean" => "The :attribute field must be true or false.",
    "confirmed" => "The :attribute confirmation does not match.",
    "current_password" => "The password is incorrect.",
    "date" => "The :attribute field must be a valid date.",
    "date_equals" => "The :attribute field must be a date equal to :date.",
    "date_format" => "The :attribute field must match the format :format.",
    "decimal" => "The :attribute field must have :decimal decimal places.",
    "declined" => "The :attribute field must be declined.",
    "declined_if" => "The :attribute field must be declined when :other is :value.",
    "different" => "The :attribute and :other fields must be different.",
    "digits" => "The :attribute field must be :digits digits.",
    "digits_between" => "The :attribute field must be between :min and :max digits.",
    "dimensions" => "The :attribute field has invalid image dimensions.",
    "distinct" => "The :attribute field has a duplicate value.",
    "doesnt_end_with" => "The :attribute field must not end with one of the following: :values.",
    "doesnt_start_with" => "The :attribute field must not start with one of the following: :values.",
    "email" => "The :attribute field must be a valid email address.",
    "ends_with" => "The :attribute field must end with one of the following: :values.",
    "enum" => "The selected :attribute is invalid.",
    "exists" => "The selected :attribute is invalid.",
    "file" => "The :attribute field must be a file.",
    "filled" => "The :attribute field must have a value.",
    "gt" => [
        "array" => "The :attribute field must have more than :value items.",
        "file" => "The :attribute field must be greater than :value kilobytes.",
        "numeric" => "The :attribute field must be greater than :value.",
        "string" => "The :attribute field must be greater than :value characters.",
    ],
    "gte" => [
        "array" => "The :attribute field must have :value items or more.",
        "file" => "The :attribute field must be at least :value kilobytes.",
        "numeric" => "The :attribute field must be greater than or equal to :value.",
        "string" => "The :attribute field must be at least :value characters.",
    ],
    "image" => "The :attribute field must be an image.",
    "in" => "The selected :attribute is invalid.",
    "in_array" => "The :attribute field does not exist in :other.",
    "integer" => "The :attribute field must be an integer.",
    "ip" => "The :attribute field must be a valid IP address.",
    "ipv4" => "The :attribute field must be a valid IPv4 address.",
    "ipv6" => "The :attribute field must be a valid IPv6 address.",
    "json" => "The :attribute field must be a valid JSON string.",
    "lowercase" => "The :attribute field must be lowercase.",
    "lt" => [
        "array" => "The :attribute field must have fewer than :value items.",
        "file" => "The :attribute field must be less than :value kilobytes.",
        "numeric" => "The :attribute field must be less than :value.",
        "string" => "The :attribute field must be less than :value characters.",
    ],
    "lte" => [
        "array" => "The :attribute field must not have more than :value items.",
        "file" => "The :attribute field must not exceed :value kilobytes.",
        "numeric" => "The :attribute field must not exceed :value.",
        "string" => "The :attribute field must not exceed :value characters.",
    ],
    "mac_address" => "The :attribute field must be a valid MAC address.",
    "max" => [
        "array" => "The :attribute field must not have more than :max items.",
        "file" => "The :attribute field must not exceed :max kilobytes.",
        "numeric" => "The :attribute field must not exceed :max.",
        "string" => "The :attribute field must not exceed :max characters.",
    ],
    "max_digits" => "The :attribute field must not have more than :max digits.",
    "mimes" => "The :attribute field must be a file of type: :values.",
    "mimetypes" => "The :attribute field must be a file of type: :values.",
    "min" => [
        "array" => "The :attribute field must have at least :min items.",
        "file" => "The :attribute field must be at least :min kilobytes.",
        "numeric" => "The :attribute field must be at least :min.",
        "string" => "The :attribute field must be at least :min characters.",
    ],
    "min_digits" => "The :attribute field must have at least :min digits.",
    "multiple_of" => "The :attribute field must be a multiple of :value.",
    "not_in" => "The selected :attribute is invalid.",
    "not_regex" => "The :attribute field format is invalid.",
    "numeric" => "The :attribute field must be a number.",
    "password" => [
        "letters" => "The :attribute field must contain at least one letter.",
        "mixed" => "The :attribute field must contain at least one uppercase and one lowercase letter.",
        "numbers" => "The :attribute field must contain at least one number.",
        "symbols" => "The :attribute field must contain at least one special character.",
        "uncompromised" => "The given password has appeared in a data leak. Please choose a different one.",
    ],
    "present" => "The :attribute field must be present.",
    "prohibited" => "The :attribute field is prohibited.",
    "prohibited_if" => "The :attribute field is prohibited when :other is :value.",
    "prohibited_unless" => "The :attribute field is prohibited unless :other is in :values.",
    "prohibits" => "The :attribute field prohibits the presence of :other.",
    "regex" => "The :attribute field format is invalid.",
    "required" => "The :attribute field is required.",
    "required_array_keys" => "The :attribute field must contain entries for: :values.",
    "required_if" => "The :attribute field is required when :other is :value.",
    "required_if_accepted" => "The :attribute field is required when :other is accepted.",
    "required_unless" => "The :attribute field is required unless :other is in :values.",
    "required_with" => "The :attribute field is required when :values is present.",
    "required_with_all" => "The :attribute field is required when all :values are present.",
    "required_without" => "The :attribute field is required when :values is not present.",
    "required_without_all" => "The :attribute field is required when none of :values are present.",
    "same" => "The :attribute and :other fields must match.",
    "size" => [
        "array" => "The :attribute field must contain :size items.",
        "file" => "The :attribute field must be :size kilobytes.",
        "numeric" => "The :attribute field must be :size.",
        "string" => "The :attribute field must be :size characters.",
    ],
    "starts_with" => "The :attribute field must start with one of the following: :values.",
    "string" => "The :attribute field must be a string.",
    "timezone" => "The :attribute field must be a valid time zone.",
    "unique" => "The :attribute has already been taken.",
    "uploaded" => "The :attribute failed to upload.",
    "uppercase" => "The :attribute field must be uppercase.",
    "url" => "The :attribute field must be a valid URL.",
    "ulid" => "The :attribute field must be a valid ULID.",
    "uuid" => "The :attribute field must be a valid UUID.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    "custom" => [
        // Complain
        "complain" => [
            "array"   => "Invalid complaint data format.",
        ],
        "complain.url" => [
            "required" => "The material URL was not provided.",
            "url" => "The material URL must be a valid link.",
        ],
        "complain.theme" => [
            "required" => "Select a complaint reason.",
        ],
        "complain.message" => [
            "required" => "Enter the complaint text.",
            "max" => "The complaint description must not exceed :max characters."
        ],

        // Appeal
        "appeal.theme" => [
            "required" => "Select an inquiry subject.",
        ],
        "appeal.message" => [
            "required" => "Enter the inquiry text.",
            "max" => "The inquiry text must not exceed :max characters."
        ],

        // BG Manager
        "bg_image" => [
            "required" => "Select a background image.",
        ],
        "material_type" => [
            "required" => "Material type was not provided.",
            "in" => "Invalid material type.",
        ],
        "material_id" => [
            "required" => "Material ID was not provided.",
        ],

        // Media Manager
        "media" => [
            "required"   => "Select media.",
            "array"   => "Invalid media data format.",
        ],
        "media.id" => [
            "string"   => "Invalid media ID format.",
        ],
        "media.old_name" => [
            "max"   => "The old file name must not exceed :max characters.",
        ],
        "media.new_name" => [
            "max"   => "The new file name must not exceed :max characters.",
            "not_regex" => "The name contains forbidden characters (\\, /, :, *, ?, \", <, >, |, etc.).",
        ],
        "media.path" => [
            "string" => "The media path must be in the correct format.",
        ],
        "media.files.*" => [
            "mimes" => "The file has an unsupported type. Allowed formats: :values.",
            "max"   => "The file is too large. Maximum size must not exceed 250 MB.",
        ],
        "media.order" => [
            "required" => "Unable to determine media file sorting order.",
            "json"     => "The file sorting order must be in the correct format (JSON).",
        ],
        "media.order_items.*.name" => [
            "required" => "File name in sorting data is required.",
            "max"     => "File name in sorting data must not exceed :max characters.",
        ],
        "media.name.max_bytes" => "The media file name is too long.",

        // Menu
        "menu" => [
            "array"   => "Invalid menu data format.",
        ],
        "menu.locale" => [
            "in"   => "This language is not used in the System.",
        ],
        "menu.item_id" => [
            "exists"   => "Menu item with the provided ID does not exist.",
        ],
        "menu.parent_id" => [
            "exists"   => "Parent menu item with the provided ID does not exist.",
        ],
        "menu.title" => [
            "required"   => "Enter the menu item title.",
        ],
        "menu.order_position" => [
            "required"   => "Specify the menu item position.",
        ],

        // Statistic
        "key" => [
            "required" => "Key was not provided.",
            "in" => "The key must be valid.",
        ],
        "model_type" => [
            "required" => "Model type was not provided.",
            "in" => "Non-existent model type.",
        ],
        "model_id" => [
            "required" => "Model ID was not provided.",
            "exists" => "The model does not exist.",
        ],

        // Consumer Settings
        "consumerType" => [
            "required" => "User type was not provided.",
            "in" => "Non-existent user type.",
        ],

        // Other
        "unique_value" => [
            "unique" => "This value is already in use.",
        ],
        "dotTargetKey" => [
            "required" => "Key was not provided.",
        ],
        "alias" => [
            "regex" => "Invalid alias format.",
            "unique" => "The alias is already in use.",
            "exists" => "The material does not exist.",
        ],
        "id" => [
            "exists" => "The model does not exist.",
        ],
        "type" => [
            "required" => "Model type was not provided.",
            "in" => "Invalid model type.",
        ],
        "locale" => [
            "required" => "Select a language.",
            "in" => "This language is not used in the System.",
        ],
        "layout" => [
            "required" => "Layout settings were not provided.",
            "json" => "Invalid layout data format.",
        ],
        "password" => [
            "required" => "Enter a password.",
            "min" => "The password must contain at least :min characters.",
            "confirmed" => "Repeat the entered password.",
        ],
        "permissions" => [
            "required" => "Specify this user's permissions.",
            "json" => "Invalid permissions data format.",
        ],
        "email" => [
            "required" => "Enter an email.",
            "email" => "Enter a valid email format.",
            "regex" => "Enter a valid email format.",
            "unique" => "This email is already in use.",
            "required_if" => "Enter a phone number or email.",
        ],
        "phone" => [
            "required" => "Enter a phone number.",
            "json" => "Invalid phone number data format.",
            "required_if" => "Enter a phone number or email.",
        ],
        "emails" => [
            "json" => "Invalid email addresses data format.",
        ],
        "phones" => [
            "json" => "Invalid phone numbers data format.",
        ],
        "links" => [
            "json" => "Invalid links data format.",
        ],
        "location" => [
            "json" => "Invalid location data format.",
        ],
        "social_networks" => [
            "json" => "Invalid social networks data format.",
        ],
        "title" => [
            "required" => "Enter a title.",
        ],
        "description" => [
            "required" => "Enter a description.",
            "max" => "The description must not exceed :max characters.",
        ],
        "category_id" => [
            "exists" => "The section does not exist.",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    "attributes" => [
        // Complain
        "complain" => "complaint",
        "complain.url" => "material URL",
        "complain.theme" => "complaint reason",
        "complain.message" => "complaint text",

        // Appeal
        "appeal.theme" => "inquiry subject",
        "appeal.message" => "inquiry text",

        // BG Manager
        "bg_image" => "background image",
        "material_type" => "material type",
        "material_id" => "material ID",

        // Media Manager
        "media" => "media",
        "media.id" => "file ID",
        "media.old_name" => "old file name",
        "media.new_name" => "new file name",
        "media.path" => "file path",
        "media.files" => "files",
        "media.files.*" => "file",
        "media.order" => "sorting order",

        // Menu
        "menu" => "menu",
        "menu.locale" => "language",
        "menu.item_id" => "menu item ID",
        "menu.parent_id" => "parent menu item ID",
        "menu.title" => "menu item title",
        "menu.order_position" => "menu item position",

        // Statistic
        "key" => "statistic key",
        "model_type" => "model type",
        "model_id" => "model ID",

        // Consumer Settings
        "consumerType" => "user type",

        // Other
        "dotTargetKey" => "settings key",
        "layout" => "layout",
        "emails" => "email addresses",
        "phones" => "phone numbers",
        "links" => "links",
        "email" => "email",
        "phone" => "phone number",
        "password" => "password",
        "password_confirmation" => "repeat password",
        "description" => "description",
        "permissions" => "permissions",
        "location" => "location",
        "social_networks" => "social networks",
        "id" => "ID",
        "category_id" => "section",
        "type" => "model type",
        "title" => "title",
        "locale" => "language",
        "unique_value" => "this value",
        "alias" => "alias",
    ],
];
