<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_a_token_for_an_existing_user()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertJsonStructure(['token']);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_not_get_a_token_for_wrong_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_can_not_get_a_token_for_a_non_existing_user()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'doesnotexist@email.com',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_can_update_the_users_password()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $oldPassword = 'password';
        $newPassword = 'newpassword';

        $response = $this->putJson('/api/update-password', [
            'old_password' => $oldPassword,
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_not_update_the_users_password_without_confirmation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $oldPassword = 'password';
        $newPassword = 'newpassword';

        $response = $this->putJson('/api/update-password', [
            'old_password' => $oldPassword,
            'new_password' => $newPassword,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_only_updates_the_users_password_authenticated_request()
    {
        $user = User::factory()->create();
        $oldPassword = 'password';
        $newPassword = 'newpassword';

        $response = $this->putJson("/api/users/$user->id", [
            'old_password' => $oldPassword,
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
