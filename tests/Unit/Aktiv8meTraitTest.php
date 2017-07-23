<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Kitano\Aktiv8me\Aktiv8me;

class Aktiv8meTraitTest extends TestCase
{
    use Aktiv8me;

    public function testCheckExpiredTokens()
    {
        $good_token = (object) [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $bad_token = (object) [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()->subMinutes(10),
        ];

        config(['aktiv8me.token_expires' => 5]);

        $this->assertFalse($this->tokenIsExpired($good_token));
        $this->assertTrue($this->tokenIsExpired($bad_token));
    }

    public function testCanAutoLogin()
    {
        config(['aktiv8me.max_tokens' => 1]);
        config(['aktiv8me.auto_login' => 1]);

        $this->assertFalse($this->canAutoLogin());

        config(['aktiv8me.max_tokens' => 0]);

        $this->assertTrue($this->canAutoLogin());

        config(['aktiv8me.max_tokens' => 1]);
        config(['aktiv8me.auto_login' => 0]);

        $this->assertFalse($this->canAutoLogin());
    }

    public function testCanAutoResendToken()
    {
        config(['aktiv8me.auto_resend' => 0]);

        $this->assertFalse($this->canAutoResendToken());

        config(['aktiv8me.auto_resend' => 1]);

        $this->assertTrue($this->canAutoResendToken());
    }

    public function testCanGenerateAlphaNumericTokens()
    {
        $this->assertInternalType('string', Aktiv8me::generateToken());
        $this->assertTrue(ctype_alnum(Aktiv8me::generateToken()));
        $this->assertEquals(strlen(Aktiv8me::generateToken()), 64);
    }

    public function testCanSendToken()
    {
        config(['aktiv8me.max_tokens' => 0]);

        $this->assertFalse($this->canSendToken(1));
        $this->assertFalse($this->canSendToken(0));

        config(['aktiv8me.max_tokens' => 3]);

        $this->assertTrue($this->canSendToken(0));
        $this->assertTrue($this->canSendToken(1));
        $this->assertTrue($this->canSendToken(2));
        $this->assertFalse($this->canSendToken(3));
    }

    public function testCanSetAstatus()
    {
        $result1 = $this->setStatus('title', 'message');
        $result2 = $this->setStatus('title', 'message', 404);
        $result3 = $this->setStatus('title', 'message', 202);
        $result4 = $this->setStatus('title', 'message', false);
        $result5 = $this->setStatus('title', 'message', 500, 'danger');

        $expected = [
            'authenticated' => false,
            'title' => 'title',
            'message' => 'message',
            'user' => null,
        ];
        $expected1 = array_merge($expected, ['http_code' => 200, 'http_message' => 'OK', 'type' => 'success']);
        $expected2 = array_merge($expected, ['http_code' => 404, 'http_message' => 'Not Found', 'type' => 'danger']);
        $expected3 = array_merge($expected, ['http_code' => 202, 'http_message' => 'Accepted', 'type' => 'warning']);
        $expected4 = array_merge($expected, ['http_code' => 200, 'http_message' => 'OK', 'type' => 'info']);
        $expected5 = array_merge($expected, ['http_code' => 500, 'http_message' => 'Internal Server Error', 'type' => 'danger']);

        $this->assertEquals($expected1, $result1);
        $this->assertEquals($expected2, $result2);
        $this->assertEquals($expected3, $result3);
        $this->assertEquals($expected4, $result4);
        $this->assertEquals($expected5, $result5);
    }

    public function testCanSetAstatusWithArrays()
    {
        $result1 = $this->setStatus(['title', 'message']);
        $result2 = $this->setStatus(['title', 'message', 404]);
        $result3 = $this->setStatus(['title', 'message', 202]);
        $result4 = $this->setStatus(['title', 'message', false]);
        $result5 = $this->setStatus(['title', 'message', 500, 'danger']);

        $expected = [
            'authenticated' => false,
            'title' => 'title',
            'message' => 'message',
            'user' => null,
        ];
        $expected1 = array_merge(
            $expected,
            ['http_code' => 200, 'http_message' => 'OK', 'type' => 'success']
        );
        $expected2 = array_merge(
            $expected,
            ['http_code' => 404, 'http_message' => 'Not Found', 'type' => 'danger']
        );
        $expected3 = array_merge(
            $expected,
            ['http_code' => 202, 'http_message' => 'Accepted', 'type' => 'warning']
        );
        $expected4 = array_merge(
            $expected,
            ['http_code' => 200, 'http_message' => 'OK', 'type' => 'info']
        );
        $expected5 = array_merge(
            $expected,
            ['http_code' => 500, 'http_message' => 'Internal Server Error', 'type' => 'danger']
        );

        $this->assertEquals($expected1, $result1);
        $this->assertEquals($expected2, $result2);
        $this->assertEquals($expected3, $result3);
        $this->assertEquals($expected4, $result4);
        $this->assertEquals($expected5, $result5);
    }

    public function testEmailValidatorInstance()
    {
        $this->assertInstanceof('Illuminate\Validation\Validator', $this->emailValidator([]));
    }

    public function testRegisterValidatorInstance()
    {
        $this->assertInstanceof('Illuminate\Validation\Validator', $this->registerValidator([]));
    }
}
