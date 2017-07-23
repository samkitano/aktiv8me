<?php

namespace Tests\Feature;

use App\User;
use App\RegistrationToken;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class DatabaseTest
 * @package Tests\Feature
 *
 * DO NOT CHANGE TESTS ORDER
 */
class DatabaseTest extends TestCase
{
    use DatabaseMigrations;

    protected $users;

    public function setUp()
    {
        parent::setUp();

        $users = factory(User::class, 3)
            ->create()
            ->each(function ($u) {
                $u->codes()->save(factory(RegistrationToken::class)->make());
            });

        $this->users = $users;
    }

    public function testDatabase()
    {
        $user = factory(User::class)->create()->toArray();
        $token = factory(RegistrationToken::class)->create()->toArray();

        $this->assertDatabaseHas(
            'users',
            array_except($user, ['verified', 'password', 'codes'])
        );
        $this->assertDatabaseHas(
            'registration_tokens',
            $token
        );
    }

    public function testModelRelationInstances()
    {
        $this->assertInstanceof(
            'Illuminate\Database\Eloquent\Relations\hasMany',
            $this->users->first()->codes()
        );

        $this->assertInstanceof(
            'Illuminate\Database\Eloquent\Relations\BelongsTo',
            RegistrationToken::where('user_id', $this->users->first()->id)->first()->user()
        );
    }

    public function testUserFindByEmail()
    {
        $this->assertEquals(
            $this->users->first()->toArray(),
            User::findByEmail($this->users->first()->email)->toArray()
        );
    }

    public function testUserGetTokens()
    {
        $this->assertEquals(
            $this->users->first()->codes,
            User::getTokens($this->users->first()->email)
        );
    }

    public function testUsersAreNotVerified()
    {
        foreach ($this->users as $user) {
            $this->assertFalse($user->verified);
        }
    }

    public function testRegistrationTokenDeleteCodeActivatesUser()
    {
        RegistrationToken::deleteCode(User::first()->id);

        $this->assertTrue(RegistrationToken::count() === 2);
        $this->assertTrue(User::first()->verified);
    }

    public function testRegistrationTokenCreateFor()
    {
        $user = factory(User::class)->create();

        RegistrationToken::createFor($user);

        $this->assertFalse(User::find($user->id)->verified);
        $this->assertTrue(RegistrationToken::count() === 4);
    }

    public function testRegistrationTokenFindCodes()
    {
        $this->assertEquals(
            RegistrationToken::findCodes($this->users->first()->id)->first(),
            $this->users->first()->codes->first()
        );
    }

    public function testRegistrationTokenFindToken()
    {
        $this->assertEquals(
            RegistrationToken::findToken($this->users->first()->codes->first()->token),
            $this->users->first()->codes->first()
        );
    }

    public function testRegistrationTokenMakeToken()
    {
        RegistrationToken::makeToken($this->users->first()->email);

        $this->assertTrue(user::find($this->users->first()->id)->codes->count() === 2);
    }

    public function testRegistrationTokenUpdateFor()
    {
        $user = $this->users->first();
        $old_token = $user->codes->first()->token;
        $updated_token = RegistrationToken::updateFor($user);

        $this->assertNotEquals($updated_token->token, $old_token);
        $this->assertInternalType('string', $updated_token->token);
        $this->assertTrue(ctype_alnum($updated_token->token));
    }

    public function testTokenInternals()
    {
        foreach (User::all() as $user) {
            foreach ($user->codes as $token) {
                $this->assertInternalType('string', $token->token);
                $this->assertTrue(ctype_alnum($token->token));
                $this->assertEquals(strlen($token->token), 64);
            }
        }
    }
}
