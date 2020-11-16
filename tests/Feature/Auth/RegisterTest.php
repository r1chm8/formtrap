<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_sign_up_for_an_account()
    {
        $response = $this->post('register', $data = $this->validData());

        $response->assertRedirect('/');

        $user = User::first();

        $this->assertAuthenticatedAs($user);

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertTrue(Hash::check($data['password'], $user->password));
    }

    /** @test */
    public function the_name_field_is_required()
    {
        $response = $this->post('register', $this->validData([
            'name' => ''
        ]));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_email_field_is_required()
    {
        $response = $this->post('register', $this->validData([
            'email' => ''
        ]));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_given_email_must_be_a_valid_email_address()
    {
        $response = $this->post('register', $this->validData([
            'email' => 'not an email'
        ]));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_given_email_must_not_have_been_taken()
    {
        $user = factory(User::class)->create();

        $response = $this->post('register', $this->validData([
            'email' => $user->email
        ]));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_password_field_is_required()
    {
        $response = $this->post('register', $this->validData([
            'password' => ''
        ]));

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_given_password_must_be_at_least_6_characters()
    {
        $response = $this->post('register', $this->validData([
            'password' => 'short'
        ]));

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_given_password_must_be_confirmed()
    {
        $response = $this->post('register', $this->validData([
            'password_confirmation' => 'not a match'
        ]));

        $response->assertSessionHasErrors('password');
    }

    protected function validData(array $overrides = [])
    {
        $password = 'password';

        return array_merge([
            'name' => 'Jack',
            'email' => 'jcrobertson97@gmail.com',
            'password' => $password,
            'password_confirmation' => $password
        ], $overrides);
    }
}
