<?php

/*
|--------------------------------------------------------------------------
| Aktiv8me Configuration File
|--------------------------------------------------------------------------
|
| Aktiv8me allows you to seamlessly verify your newly registered users by email.
| It works by taking advantage of the handy Laravel's default Authentication
| System, and by adding the verification funcionality with minimum impact.
| In this file you can set some basic options to better suit your needs.
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

    'welcome_email' => true,

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
    | The token count will NOT be increased. Token will be updated instead.
    | If disabled, users will have to  go to  the Register Form, and ask
    | for a new token, provided 'max_tokens' is enabled and the limit
    | has not been reached.
    |
    | WARNING: If disabled, make sure max_tokens is NOT disabled and is
    | set to any value greater than 1! Otherwise, users will have to
    | register all over again, with a different email address.
    |
    | Default:  true
    | Disabled: 0 or false
    | Enabled:  1 or true
    |
    */

    'auto_resend' => true,

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

    'auto_login' => true,

];
