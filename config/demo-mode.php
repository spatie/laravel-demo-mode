<?php

return [

    /**
     * This is the master switch to enable demo mode.
     */
    'enabled' => env('DEMO_MODE_ENABLED', true),

    /**
     * Visitors that go an url that is protected by demo mode will be redirected.
     * to this url.
     */
    'redirect_unauthorized_users_to_url' => '/under-construction',

    /**
     * After have been granted access visitors will be redirected to this url.
     */
    'redirect_authorized_users_to_url' => '/',

    /**
     * The following IP addresses will automatically have access to the app
     * without having to pass the `demoAccess` route.
     */
    'authorized_ips' => [
        
    ],

    /**
     * If strict mode is enabled then only the IP addresses listed in `authorized_ips` are allowed.
     * Vistors won't be able to gain access by visiting the  `demoAccess` route anymore.
     */
    'strict_mode' => false,

];
