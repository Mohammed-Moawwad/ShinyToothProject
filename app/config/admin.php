<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Email
    |--------------------------------------------------------------------------
    | The email address that is treated as the system administrator.
    | Loaded from the ADMIN_EMAIL environment variable. Using config()
    | instead of env() directly ensures this works after config:cache.
    */
    'email' => env('ADMIN_EMAIL', 'admin@shinytooth.com'),
];
