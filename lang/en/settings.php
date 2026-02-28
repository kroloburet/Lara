<?php

return [
    "sitemap" => [
        "title" => "Sitemap Generator",
        "desc" => "Update Sitemap",
        "hint" => "To help search engines index new materials faster, periodically update the Sitemap or enable automatic updates.",
        "last_update" => "Last updated: ",
        "Refresh" => "Refresh",
        "View" => "View",
        "Not_found" => "Sitemap not found or is empty. Please update the Sitemap.",
        "refresh_done" => "Sitemap updated",
    ],
    "access" => [
        "title" => "Resource Access",
        "label" => "Access Mode",
        "hint" => "To temporarily restrict access to everyone except Administrators and Moderators, select the appropriate access mode. In <q>Maintenance</q> mode, all users except logged-in Administrators and Moderators will be redirected to a maintenance page. In <q>Access Allowed</q> mode, the resource operates normally.",
        "allowed" => "Access Allowed",
        "dev" => "Maintenance",
    ],
    "moderators" => [
        "title" => "Moderators",
        "Count" => "Total Moderators:",
        "Manage" => "Manage",
    ],
    "timezone" => [
        "title" => "Time Zone",
        "err_auto_detect" => "<h5>An error occurred</h5>Unfortunately, we could not automatically detect your time zone. Please select it manually from the list.",
        "label" => "Select your time zone",
        "hint" => "All dates and times will be displayed in your time zone. If you are unsure of your time zone, click the <i class='fa-solid fa-location-crosshairs'></i> icon to detect it automatically.",
        "get_current" => "Detect my time zone automatically",
    ],
    "layout" => [
        "title" => "Layout",
        "desc" => "Quickly set the default layout for all new materials you create and override layout settings in all materials you've already created. Select the material type, set the layout, choose the purpose of the settings and click <q>Apply</q>. At any time, you can adjust the layout for each individual material independently of these settings on the page of creating or editing a material.",
        "save_changes_confirm" => "<h5>Please wait</h5>Save your settings for this material type before switching to another?",
        "save_existent_materials_confirm" => "<h5>Confirm action</h5>Layout settings in all already created materials of the selected type will be overwritten with these settings. All new materials of the selected type will default to these layout settings.",
        "material_type" => [
            "label" => "Material Type",
            "hint" => "Select the material type to configure the default layout for all new materials of this type.",
        ],
        "header" => [
            "label" => "Header with background image",
            "hint" => "Determine whether to show the header with the material's background image on the page.",
        ],
        "desktop" => [
            "label" => "Desktop View",
            "hint" => "Define how the material page layout will appear on devices wider than 800px.",
            "right-aside" => "Right column",
            "left-aside" => "Left column",
            "top-aside" => "Top column",
            "bottom-aside" => "Bottom column",
            "not-aside" => "No column",
        ],
        "mobile" => [
            "label" => "Mobile View",
            "hint" => "Define how the material page layout will appear on devices narrower than 800px.",
            "top-aside-adaptive" => "Top column",
            "bottom-aside-adaptive" => "Bottom column",
            "not-aside-adaptive" => "No column",
        ],
        "layout_max_width" => [
            "label" => "Maximum layout width (px)",
            "hint" => "Determine what the maximum width of the layout will be on the PC. The recommended width is 1300px",
        ],
        "aside_width" => [
            "label" => "Layout column width (%)",
            "hint" => "Determine what the layout column width will be as a percentage of the layout width. Recommended width 30%",
        ],
        "save_opt" => [
            "label" => "Assignment of settings",
            "hint" => "You can assign layout settings only to new (to be created by you) materials of the selected type, or to materials of the selected type that have already been created and are new.",
            "only_new" => "Only for new materials",
            "existent_and_new" => "For already created and new materials",
        ],
    ],
];
