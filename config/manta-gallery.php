<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Manta Gallery Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can adjust the configuration for the Manta Gallery package.
    |
    */

    // Route prefix for the gallery module
    'route_prefix' => 'cms/gallery',

    // Database settings
    'database' => [
        'table_name' => 'manta_galleries',
    ],

    // UI settings
    'ui' => [
        'items_per_page' => 25,
        'show_breadcrumbs' => true,
    ],

];
