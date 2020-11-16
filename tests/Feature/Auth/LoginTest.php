<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $email = 'jcrobertson97@gmail.com';

    protected $password = 'password';

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'name' => 'Jack',
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ]);
    }

    /** @test */
    public function a_user_can_sign_in_to_their_account()
    {
        $response = $this->post('login', $this->validData());

        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function a_user_cannot_sign_in_with_invalid_credentials()
    {
        $response = $this->post('login', $this->validData([
            'password' => 'incorrect'
        ]));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_field_is_required()
    {
        $response = $this->post('login', $this->validData([
            'email' => ''
        ]));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_password_field_is_required()
    {
        $response = $this->post('login', $this->validData([
            'password' => ''
        ]));

        $response->assertSessionHasErrors('password');
    }

    protected function validData(array $overrides = [])
    {
        return array_merge([
            'email' => $this->email,
            'password' => $this->password
        ], $overrides);
    }
}
