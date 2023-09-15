<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\User;
class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_a_registration_request(): void
    {

        $response = $this->json('POST', '/api/registration', ['name'=>'name', 'email'=>'email@gmail.com', 'password'=>'Passw@rd',
        'password_confirmation'=>'Passw@rd'], ['Accept'=>'application/json', 'Content-Type'=>'application/json']);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['status', 'message', 'token']);

    }

    public function test_user_cannot_register_with_existing_email():void
    {
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/registration', ['name'=>$user->name, 'email'=>$user->email, 'password'=>'Passw@rd',
        'password_confirmation'=>'Passw@rd'], ['Accept'=>'application/json', 'Content-Type'=>'application/json']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
