<?php

namespace Kitano\Aktiv8me;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\Aktiv8me\TokenRenewed;
use App\Notifications\Aktiv8me\ConfirmEmail;
use App\Notifications\Aktiv8me\AlreadyActive;
use App\Notifications\Aktiv8me\EmailConfirmed;
use Symfony\Component\HttpFoundation\Response;

trait ActivatesUsers
{
    /**
     * Check if email activation is enabled
     *
     * @return mixed
     */
    protected function aktiv8enabled()
    {
        return config('aktiv8me.max_tokens');
    }

    /**
     * Check if auto-login is enabled
     *
     * @return bool
     */
    protected function autoLoginEnabled()
    {
        $auto_login = config('aktiv8me.auto_login');

        return isset($auto_login) && $auto_login;
    }

    /**
     * Check if auto-login is possible
     *
     * @return bool
     */
    protected function canAutoLogin()
    {
        return (! $this->aktiv8enabled()) && $this->autoLoginEnabled();
    }

    /**
     * Check if an expired token can be updated and auto-resent
     *
     * @return bool
     */
    protected function canAutoResendToken()
    {
        $auto_resend = config('aktiv8me.auto_resend');

        return $auto_resend && isset($auto_resend);
    }

    /**
     * Check if a token can be sent
     *
     * @param int $count Tokens Count
     *
     * @return bool
     */
    protected function canSendToken($count)
    {
        $max_tokens = config('aktiv8me.max_tokens');

        return $max_tokens && $count < $max_tokens;
    }

    /**
     * Validates an email
     *
     * @param $input
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function emailValidator($input)
    {
        // usually we would also validate |exists:users
        // but then again, we don't want to disclose
        // any info about our registered users.
        return Validator::make($input, [
            'email' => 'required|email|max:255',
        ]);
    }

    /**
     * Generates a random token
     *
     * @return string
     */
    public static function generateToken()
    {
        return hash_hmac('sha256', str_random(64), config('app.key'));
    }

    /**
     * @param $code
     *
     * @return string
     */
    protected function getStatusType($code)
    {
        if (!$code) {
            return 'info';
        }

        if ($code === 202) {
            return 'warning';
        }

        return $code >= 400 ? 'danger' : 'success';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param $input
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function registerValidator($input)
    {
        return Validator::make($input, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Send the Activation Email
     *
     * @param           $user
     * @param string    $token
     * @param null|int  $count
     *
     * @return array
     */
    protected function sendActivationEmail($user, $token, $count = null)
    {
        $user->notify(new ConfirmEmail($token));

        if (isset($count)) {
            $max = $this->aktiv8enabled();
            $left = $max - $count;
            return [
                trans('aktiv8me.status.account_confirmation'),
                trans('aktiv8me.status.token_resent', ['count' => $count, 'left' => $left]),
                200
            ];
        }

        $message = trans(
            'aktiv8me.status.user_registered',
            ['username' => $user->name, 'email' => $user->email]
        );

        if ($this->tokenExpires()) {
            $message .= trans('aktiv8me.status.token_expires', ['time' => $this->tokenExpiration().' min.']);
        }

        return [
            trans('aktiv8me.status.registration'),
            $message,
            201
        ];
    }

    /**
     * Send an Activation Email with an updated token
     *
     * @param $user
     * @param $token
     *
     * @return array
     */
    protected function sendTokenUpdatedEmail($user, $token)
    {
        $user->notify(new TokenRenewed($token));

        return [
            trans('aktiv8me.status.account_confirmation'),
            trans('aktiv8me.status.token_expired_and_resent'),
            202
        ];
    }

    /**
     * Notify account already active
     *
     * @param $user
     *
     * @return array
     */
    protected function sendUserIsActiveEmail($user)
    {
        $user->notify(new AlreadyActive($user));

        return [
            trans('aktiv8me.status.account_confirmation'),
            trans('aktiv8me.status.user_is_active'),
            false
        ];
    }

    /**
     * Send a welcome email
     *
     * @param $user
     *
     * @return $this
     */
    protected function sendWelcomeEmail($user)
    {
        if (config('aktiv8me.welcome_email')) {
            $user->notify(new EmailConfirmed($user));
        }

        return $this;
    }

    /**
     * Sets an array to carry flash messages or json responses
     *
     * @param string|array  $title
     * @param string        $message
     * @param int|bool      $http_code Setting to false will get type 'info' and default to 200
     * @param null|string   $type
     *
     * @return array
     */
    protected function setStatus($title, $message = '', $http_code = 200, $type = null)
    {
        if (is_array($title)) {
            $message = $title[1];
            $http_code = isset($title[2]) ? $title[2] : 200;
            $type = isset($title[3]) ? $title[3] : null;
            $title = $title[0];
        }

        $code = $http_code ? $http_code : 200;

        return [
            'authenticated' => Auth::check(),
            'title' => $title,
            'message' => $message,
            'http_code' => $code,
            'http_message' => Response::$statusTexts[$code],
            'type' => isset($type) ? $type : $this->getStatusType($http_code),
            'user' => Auth::check() ? Auth::user() : null,
        ];
    }

    /**
     * Gets token expiration minutes
     *
     * @return int
     */
    protected function tokenExpiration()
    {
        return (int) config('aktiv8me.token_expires');
    }

    /**
     * Check if tokens can expire
     *
     * @return bool
     */
    protected function tokenExpires()
    {
        $token_expires = config('aktiv8me.token_expires');

        return $token_expires && $token_expires > 0;
    }

    /**
     * Check if a token is expired
     *
     * @param $token
     *
     * @return bool
     */
    protected function tokenIsExpired($token)
    {
        $token_expires = config('aktiv8me.token_expires');

        return $token_expires && $token->updated_at->addMinutes($token_expires) < Carbon::now();
    }
}
