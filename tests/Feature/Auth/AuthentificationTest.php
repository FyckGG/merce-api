<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\User;
use Constants\FactoryConstants\UserFactoryConstants;
use Laravel\Sanctum\Sanctum;

class AuthentificationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_a_login_request(): void
    {
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/login', ['email'=> $user->email, 'password'=>UserFactoryConstants::PASSWORD], 
        ['Accept'=>'application/json', 'Content-Type'=>'application/json']);


        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['status', 'message', 'token']);
    }

    public function test_user_cannot_login_with_invalid_email(): void
    {
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/login', ['email'=>'falseemail@gmail.com', 'password'=>UserFactoryConstants::PASSWORD], 
        ['Accept'=>'application/json', 'Content-Type'=>'application/json']);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/login', ['email'=>$user->email, 'password'=>'falsePassw@rd'], 
        ['Accept'=>'application/json', 'Content-Type'=>'application/json']);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_a_logout_request(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('POST','/api/logout', [], ['Accept'=>'application/json', 'Content-Type'=>'application/json']);

        $response->assertStatus(Response::HTTP_OK);
    }
}
