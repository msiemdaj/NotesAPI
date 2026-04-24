<?php

namespace Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email'    => 'test@notes.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@notes.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'token'
                ],
            ])
            ->assertJson(['success' => true]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'test@notes.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@notes.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_with_non_existent_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'test@notes.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_with_invalid_email_format(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'wrong-email',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
