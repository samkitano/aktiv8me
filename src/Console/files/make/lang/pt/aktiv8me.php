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
            'forgot'   => 'Esqueceu a password?',
            'login'    => 'Login',
            'remember' => 'Lembrar',
        ],

        'register' => [
            'activate'   => 'Activar conta?',
            'confirm'    => 'Confirmar Password',
            'name'       => 'Nome',
            'register'   => 'Registar',
        ],

        'resend' => [
            'back'   => 'Registar',
            'info'   => 'Se já está registado, verifique a sua caixa de correio, incluindo a pasta de Spam'.
                        ' e procure o email que acabamos de enviar. Se por alguma razão não o recebeu,'.
                        ' ainda pode solicitar até :max emails de activação adicionais.',
            'resend' => 'Enviar Email de Activação',
            'submit' => 'Enviar',
        ],

        'common' => [
            'email'    => 'Email',
            'password' => 'Password',
        ],
    ],

    'notifications' => [
        'confirm' => [
            'action'  => 'Activar conta',
            'line1'   => 'Obrigado por se registar, :username! '.
                         'Por favor, clique no botão abaixo para activar a sua conta.',
            'line2'   => 'Se não efectuou nenhum registo em :appname, por favor ignore este email.',
            'subject' => 'Activação de Conta',
        ],

        'isactive' => [
            'action'  => 'Reposição de Password',
            'line1'   => 'Alguém solicitou um email de activação para esta conta em :appname.',
            'line2'   => 'No entanto, esta conta encontra-se já activada. Se esqueceu a sua password, '.
                         'por favor clique no botão abaixo para efectuar uma reposição da mesma.',
            'line3'   => 'Se não solicitou a activação da conta, pode ignorar este email, '.
                         'mas tenha em conta que alguém está a tentar aceder à sua conta. '.
                         'Altere a sua password, se achar que não é suficientemente segura.',
            'subject' => 'Activação de Conta',
        ],

        'renewed' => [
            'action'  => 'Activar Conta',
            'line1'   => 'Lamentamos, :username! Tentou activar a sua conta, '.
                         'mas lamentavelmente demorou muito tempo a fazê-lo e a sua validade expirou. '.
                         'Eis um novo link para activação da sua conta. Por favor clique no botão abaixo.',
            'line2'   => 'Se não efectuou nenhum registo em :appname, por favor ignore este email.',
            'subject' => 'Activação de Conta',
        ],

        'welcome' => [
            'line1'   => 'Bem-vindo a :appname, :username!',
            'line2'   => 'O seu registo foi completado, e a sua conta activada. Desfrute do nosso website!',
            'subject' => 'Bem-vindo!',
        ],
    ],

    'status' => [
        'account_confirmed'        => 'A sua conta foi activada. Obrigado!',
        'account_confirmed_and_in' => 'A sua conta foi activada, e a sua sessão iniciada. Obrigado, :username!',
        'account_confirmation'     => 'Activação de Conta',
        'can_resend'               => 'Pode solicitar o envio de emails de activação adicionais no '.
                                      'formulário de Registo.',
        'first_login'              => 'Bem-vindo, :username! O seu registo está completo, e a sua sessão iniciada.',
        'invalid_token'            => 'Código de activação inválido!',
        'login'                    => 'Login',
        'logged_in'                => 'Sessão iniciada. Bem-vindo, :username!',
        'max_tokens'               => 'Lamentamos, mas o limite de envio de emails de activação foi atingido. '.
                                      'Verificou a pasta de Spam, na sua caixa de correio?',
        'no_can_do'                => 'Lamentamos, não há nada a fazer. Tente recuperar a sua password '.
                                      'ou registar-se, se ainda não o fez.',
        'registration'             => 'Registo',
        'token_expired'            => 'Lamentamos, o email de activação expirou. ',
        'token_expired_and_resent' => 'Tentou activar a sua conta, mas lamentavelmente demorou muito tempo a fazê-lo '.
                                      'e a validade do email de activação expirou. Por favor, verifique de novo a sua '.
                                      ' caixa de correio e localize o novo email que enviámos. '.
                                      'Desculpe o inconveniente.',
        'token_expires'            => 'Atenção: O email de activação expira em :time ',
        'token_resent'             => 'Email de activação número :count enviado. :left tentativas restantes.',
        'user_is_active'           => 'Email de activação enviado. Verifique a sua caixa de correio.',
        'user_registered'          => 'Gratos pelo registo, :username !Verifique a sua caixa de correio'.
                                      ' (:email) e siga as instruções para activar a sua conta. ',
    ],

];
