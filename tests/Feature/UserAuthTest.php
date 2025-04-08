<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $data = [
            "name" => "Mustafa test",
            "email" => "hamzawytest@gmail.com",
            "password" => "Open@@2025",
            "password_confirmation" => "Open@@2025"
        ];

        $response = $this->postJson($this->endpoint."register", $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'hamzawytest@gmail.com',
        ]);
    }

    /** @test */
    public function user_cannot_register_with_missing_data()
    {
        $data = [
            "name" => "Mustafa test", // Missing email
            "password" => "Open@@2025",
            "password_confirmation" => "Open@@2025"
        ];

        $response = $this->postJson($this->endpoint . "register", $data);

        $response->assertStatus(422); // Validation error
    }

    /** @test */
    public function user_cannot_register_with_invalid_email()
    {
        $data = [
            "name" => "Mustafa test",
            "email" => "invalid-email", // Invalid email format
            "password" => "Open@@2025",
            "password_confirmation" => "Open@@2025"
        ];

        $response = $this->postJson($this->endpoint . "register", $data);

        $response->assertStatus(422); // Validation error
    }

    /** @test */
    public function user_cannot_register_with_password_mismatch()
    {
        $data = [
            "name" => "Mustafa test",
            "email" => "hamzawytest2@gmail.com",
            "password" => "Open@@2025",
            "password_confirmation" => "DifferentPassword123"
        ];

        $response = $this->postJson($this->endpoint . "register", $data);

        $response->assertStatus(422); // Validation error
    }

    /** @test */
    public function user_cannot_register_with_weak_password()
    {
        $data = [
            "name" => "Mustafa test",
            "email" => "hamzawytest3@gmail.com",
            "password" => "short", // Weak password
            "password_confirmation" => "short"
        ];

        $response = $this->postJson($this->endpoint . "register", $data);

        $response->assertStatus(422); // Validation error
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
            'password' => Hash::make('Open@@2025'),
        ]);

        $data = [
            'email' => 'testuser@gmail.com',
            'password' => 'Open@@2025',
        ];

        $response = $this->postJson($this->endpoint . 'login', $data);

        $response->assertStatus(200); // Successful login
        $response->assertJsonStructure([
            'message',
            'token',
        ]);

    }

    /** @test */
    public function user_cannot_login_with_invalid_email()
    {
        $data = [
            'email' => 'invaliduser@gmail.com',
            'password' => 'Open@@2025',
        ];

        $response = $this->postJson($this->endpoint . 'login', $data);

        $response->assertStatus(422);
    }
    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
            'password' => Hash::make('Open@@2025'),
        ]);

        $data = [
            'email' => 'testuser@gmail.com',
            'password' => 'wrongpassword', // Invalid password
        ];

        $response = $this->postJson($this->endpoint . 'login', $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function user_cannot_login_with_missing_credentials()
    {
        // Test missing email
        $data = [
            'password' => 'Open@@2025',
        ];

        $response = $this->postJson($this->endpoint . 'login', $data);
        $response->assertStatus(422);

        // Test missing password
        $data = [
            'email' => 'testuser@gmail.com',
        ];

        $response = $this->postJson($this->endpoint . 'login', $data);
        $response->assertStatus(422);
    }

    /** @test */
    public function user_can_view_their_profile()
    {
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('Open@@2025'),
        ]);

        // Call login endpoint
        $response = $this->postJson($this->endpoint.'login', [
            'email' => 'testuser@gmail.com',
            'password' => 'Open@@2025',
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        // Use token to access profile
        $profileResponse = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson($this->endpoint.'user');

        $profileResponse->assertStatus(200);
    }

    /** @test */
    public function user_can_logout_successfully()
    {
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('Open@@2025'),
        ]);

        // Login and get token
        $response = $this->postJson($this->endpoint.'login', [
            'email' => 'testuser@gmail.com',
            'password' => 'Open@@2025',
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        // Perform logout
        $logoutResponse = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson($this->endpoint.'logout');

        $logoutResponse->assertStatus(200);
    }

}
