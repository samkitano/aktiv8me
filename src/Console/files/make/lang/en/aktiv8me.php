<?php

/*
|--------------------------------------------------------------------------
| Aktiv8me Translation File
|--------------------------------------------------------------------------
|
| Aktiv8me allows you to seamlessly verify your newly registered users by email.
| It works by taking advantage of the handy Laravel's default Authentication
| System, and by adding the verification funcionality with minimum impact.
| In this file you can change any output texts from flashed messages to
| notification emails.
|
*/

return [
    'forms' => [
        'login' => [
            'forgot'   => 'Forgot your password?',
            'login'    => 'Login',
            'remember' => 'Remember me',
        ],

        'register' => [
            'activate'   => 'Need to activate your account?',
            'confirm'    => 'Confirm Password',
            'name'       => 'Name',
            'register'   => 'Register',
        ],

        'resend' => [
            'back'   => 'Back to Registration',
            'info'   => 'If you are already registered, check out your inbox, including the spam folder'.
                        ' for an activation email we\'ve sent. If for some reason you didn\'t get'.
                        ' it, you can request up to :max aditional activation emails.',
            'resend' => 'Send Account Activation Email',
            'submit' => 'Send',
        ],

        'common' => [
            'email'    => 'Email Address',
            'password' => 'Password',
        ],
    ],

    'notifications' => [
        'confirm' => [
            'action'  => 'Confirm Registration',
            'line1'   => 'Thank you for registering, :username! '.
                         'Please click the button below in order to activate your account.',
            'line2'   => 'If you did not register at :appname, no further action is required.',
            'subject' => 'Account Activation',
        ],

        'isactive' => [
            'action'  => 'Reset Password',
            'line1'   => 'Someone requested an activation email for this account at :appname.',
            'line2'   => 'However, this account is already active. If you forgot your password '.
                         'please click the link below to reset your password.',
            'line3'   => 'If you did not ask for an activation email, no further action is required, '.
                         'but be aware that someone is trying to gain access to your account. '.
                         'You may want to change your password, if you feel it\'s not secure enough.',
            'subject' => 'Account Activation',
        ],

        'renewed' => [
            'action'  => 'Confirm Registration',
            'line1'   => 'Sorry, :username! You tried to activate your account, '.
                         'but unfortunately you took too long to do so, and your link expired. '.
                         'Here you have a fresh new link. All you have to do is to click the button bellow.',
            'line2'   => 'If you did not register at :appname, no further action is required.',
            'subject' => 'Account Activation',
        ],

        'welcome' => [
            'line1'   => 'Welcome to :appname, :username!',
            'line2'   => 'Your registration is now complete, and your account activated. Enjoy our website!',
            'subject' => 'Welcome!',
        ],
    ],

    'status' => [
        'account_confirmed'        => 'Your account has been activated. Thanks!',
        'account_confirmed_and_in' => 'Your account has been activated, and you are now logged in. Thanks, :username!',
        'account_confirmation'     => 'Account Activation',
        'can_resend'               => 'You can request another activation email in the Register Form.',
        'first_login'              => 'Welcome, :username! You are now registered and logged in.',
        'invalid_token'            => 'Invalid Token',
        'login'                    => 'Login',
        'logged_in'                => 'You are now logged in. Welcome back, :username!',
        'max_tokens'               => 'Sorry, activation emails limit reached. '.
                                      'Did you check your Spam folder, just in case?',
        'no_can_do'                => 'Sorry, there is nothing we can do. You are welcome to try and recover your '.
                                      'password, or to register instead, if you havn\'t done so yet.',
        'registration'             => 'Registration',
        'token_expired'            => 'Sorry, your activation link is expired. ',
        'token_expired_and_resent' => 'You tried to activate your account, but unfortunately you took too long to do '.
                                      'so, and your activation email has expired meanwhile. Please, check your inbox '.
                                      'for a fresh new email we\'ve just sent you. We apologise for any inconvenience.',
        'token_expires'            => 'Please notice: Your activation email will expire in :time',
        'token_resent'             => 'Activation email number :count sent. :left attempts left.',
        'user_is_active'           => 'Activation Email sent. Please check out your inbox.',
        'user_registered'          => 'Thanks for registering, :username ! Please check out your'.
                                      ' inbox (:email) and follow instructions to activate your account. ',
    ],

];
