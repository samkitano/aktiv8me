<?php

/*
|--------------------------------------------------------------------------
| Aktiv8me Configuration File
|--------------------------------------------------------------------------
|
| Aktiv8me allows your app to seamlessly confirm your newly registered users
| It works by taking advantage of handy Laravel's default  Authentication
| System, and adding the confirmation funcionality with minimum impact
|
| Here you can set some options to meet your app requirements.
|
| https://laravel.com/docs/5.4/configuration
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Max allowed token requests - Integer|Boolean
    |--------------------------------------------------------------------------
    |
    | This option will allow registered users to ask for aditional tokens,
    | if for some weird reason they fail to get the first email. If max
    | is reached, users will have to register again with a different
    | email address.
    |
    | WARNING: If 0 or false, email verification will be disabled.
    |
    | Default:  3
    | Disabled: 0 or false
    |
    */

    'max_tokens' => 3,

    /*
    |--------------------------------------------------------------------------
    | Welcome Email - Boolean
    |--------------------------------------------------------------------------
    |
    | Send a  Welcome Email after successful email verification.
    | If disabled, a flashed session message will do the job.
    |
    | Default:  false
    | Disabled: 0 or false
    | Enabled:  1 or true
    |
    */

    'welcome_email' => false,

    /*
    |--------------------------------------------------------------------------
    | Token Expiration - Integer|Boolean
    |--------------------------------------------------------------------------
    |
    | Expiration time for dispatched tokens,  in minutes.
    | If disabled, sent tokens will not expire at all.
    | It is  recommended to  disable this option if
    | 'max_tokens'  is disabled so that any user
    | can activate their account at any time.
    |
    | Default:  60 (1 hour)
    | Disabled: 0 or false
    |
    */

    'token_expires' => 60,

    /*
    |--------------------------------------------------------------------------
    | Auto Resend Token - Boolean
    |--------------------------------------------------------------------------
    |
    | Automatically sends a new token when an expired one is used, if enabled.
    | Otherwise, users will be able to request a new one in the Login form.
    |
    | Default:  false
    | Disabled: 0 or false
    | Enabled:  1 or true
    |
    */

    'auto_resend' => false,

    /*
    |--------------------------------------------------------------------------
    | Auto Login - Boolean
    |--------------------------------------------------------------------------
    |
    | If enabled, users will be automatically logged in upon email verification
    | or after registration, if max_tokens is disabled. If disabled, they'll
    | be redirected to a regular Login Form, and prompted for credentials.
    |
    | Default:  true
    | Disabled: 0 or false
    | Enabled:  1 or true
    |
    */

    'auto_login' => false,

];
