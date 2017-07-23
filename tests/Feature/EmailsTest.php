<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Kitano\Aktiv8me\Aktiv8me;
use MailThief\Testing\InteractsWithMail;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EmailsTest extends TestCase
{
    use Aktiv8me, DatabaseMigrations, InteractsWithMail;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();

        $this->user = $user;
    }

    public function testSendActivationEmail()
    {
        $this->sendActivationEmail($this->user, \App\RegistrationToken::makeToken($this->user->email));

        $this->seeMessageFor($this->user->email);
        $this->seeMessageWithSubject(trans('aktiv8me.notifications.confirm.subject'));
        $this->assertTrue($this->lastMessage()->contains($this->user->codes->first()->token));
    }

    public function testSendUserIsActiveEmail()
    {
        $this->sendUserIsActiveEmail($this->user);

        $this->seeMessageFor($this->user->email);
        $this->seeMessageWithSubject(trans('aktiv8me.notifications.isactive.subject'));
        $this->assertTrue($this->lastMessage()->contains(config('app.name')));
        $this->assertTrue($this->lastMessage()->contains(route('password.request')));
    }

    public function testSendWelcomeEmail()
    {
        $this->sendWelcomeEmail($this->user);

        $this->seeMessageFor($this->user->email);
        $this->seeMessageWithSubject(trans('aktiv8me.notifications.welcome.subject'));
    }

    public function testSendTokenUpdatedEmail()
    {
        $token = \App\RegistrationToken::createFor($this->user);
        $updated_token = \App\RegistrationToken::updateFor($this->user);

        $this->sendTokenUpdatedEmail($this->user, $updated_token);

        $this->seeMessageFor($this->user->email);
        $this->seeMessageWithSubject(trans('aktiv8me.notifications.confirm.subject'));
        $this->assertTrue($this->lastMessage()->contains($updated_token->token));
        $this->assertFalse($this->lastMessage()->contains($token->token));
    }
}
