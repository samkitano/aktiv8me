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
            'forgot'   => '¿Olvidaste tu contraseña?',
            'login'    => 'Inicio de sesión',
            'remember' => 'Recordar mis datos',
        ],

        'register' => [
            'activate'   => '¿Necesitas activar tu cuenta?',
            'confirm'    => 'Confirmar contraseña',
            'name'       => 'Nombre',
            'register'   => 'Registrarte',
        ],

        'resend' => [
            'back'   => 'Registrarte',
            'info'   => 'Si te has registrado, mira tu caja de correo, incluyendo la carpeta de Spam'.
                        ' y busca el email de activación que te hemos enviado. Si no lo has recibido'.
                        ' podrás solicitar hasta :max correos adicionales.',
            'resend' => 'Enviar Correo de Activación',
            'submit' => 'Enviar',
        ],

        'common' => [
            'email'    => 'Correo electrónico',
            'password' => 'Contraseña',
        ],
    ],

    'notifications' => [
        'confirm' => [
            'action'  => 'Activar Mi Cuenta',
            'line1'   => 'Gracias por registrarte, :username! '.
                         'Haz click en el botón abajo, para activar tu cuenta.',
            'line2'   => 'Si no te has registrado en :appname, por favor ignora este correo.',
            'subject' => 'Activar Cuenta',
        ],

        'isactive' => [
            'action'  => 'Cambiar Contraseña',
            'line1'   => 'Alguien solicitó un email de activación para esta cuenta en :appname.',
            'line2'   => 'Sin embargo, esta cuenta ya se encuentra activada. Si se te ha olvidado tu contraseña, '.
                         'haz click en el botón abajo, para que puedas cambiar tu contraseña.',
            'line3'   => 'Si no te has registrado en :appname, por favor ignora este correo, '.
                         'pero recuerda que alguen está intentando acceder a tu cuenta. '.
                         'Cambia tu contraseña, si te parece que no es segura.',
            'subject' => 'Activación de Cuenta',
        ],

        'renewed' => [
            'action'  => 'Activar Cuenta',
            'line1'   => 'Lo lamentamos, :username! Has intentado activar tu cuenta, '.
                         'pero desafortunamente has tardado mucho en hacerlo, y el enlace ha expirado. '.
                         'Aqui te enviamos un nuevo enlace. Solo tienes que hacer click en el botón abajo.',
            'line2'   => 'Si no te has registrado en :appname, por favor ignora este correo.',
            'subject' => 'Activación de Cuenta',
        ],

        'welcome' => [
            'line1'   => 'Bienvenido a :appname, :username!',
            'line2'   => 'Tu registro está completo, y tu cuenta fué activada. Disfruta de nuestra Web!',
            'subject' => 'Bienvenido!',
        ],
    ],

    'status' => [
        'account_confirmed'        => 'Tu cuenta fué activada. Gracias!',
        'account_confirmed_and_in' => 'Tu cuenta fué activada, y ya estás conectado. Gracias, :username!',
        'account_confirmation'     => 'Activación de Cuenta',
        'can_resend'               => 'Puedes solicitar otro correo de activación en el formulário de registro.',
        'first_login'              => 'Bienvenido, :username! Ya estás registrado y conectado.',
        'invalid_token'            => 'Código Inválido',
        'login'                    => 'Inicio de sesión',
        'logged_in'                => 'Ya estás conectado. Bienvenido, :username!',
        'max_tokens'               => 'Lo lamentamos, límite de correos de activación alcanzado. '.
                                      'Has mirado en la carpeta de Spam, por si acaso?',
        'no_can_do'                => 'Lo lamentanos, no hay nada que podamos hacer. Intenta recuperar tu contraseña, '.
                                      'o registrate, si todavia no lo has hecho.',
        'registration'             => 'Registro',
        'token_expired'            => 'Lo lamentamos, tu enlace ha expirado. ',
        'token_expired_and_resent' => 'Has intentado activar tu cuenta, pero desafortunamente has tardado mucho en '.
                                      'hacerlo, y el enlace ha expirado. Por favor, mira tu caja de correo '.
                                      'y busca un nuevo email que te hemos enviado. Perdona el inconveniente.',
        'token_expires'            => 'Atención: el enlace en el correo de activación expirará en :time ',
        'token_resent'             => 'Correo de activación número :count enviado. Te quedan :left tentativa(s).',
        'user_is_active'           => 'Correo de activación enviado. Por favor mira tu caja de correo.',
        'user_registered'          => 'Gracias por registrarte, :username! Por favor mira tu caja de correo'.
                                      ' (:email) y sigue las instrucciones para activar tu cuenta. ',
    ],

];
