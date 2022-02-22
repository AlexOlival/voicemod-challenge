<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_list_users()
    {
        User::factory()->count(9)->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertJsonCount(10, 'data');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_only_list_users_for_an_authenticated_request()
    {
        User::factory()->count(10)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/users', [
            'name' => 'New User',
            'surnames' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'country' => $this->faker->country,
            'phone' => $this->faker->randomNumber(5),
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
        ]);
    }

    /** @test */
    public function it_only_creates_a_user_for_an_authenticated_request()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'New User',
            'surnames' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'country' => $this->faker->country,
            'phone' => $this->faker->randomNumber(5),
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $oldName = $user->name;
        $newName = 'New User';

        $response = $this->putJson("/api/users/$user->id", [
            'name' => $newName,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'name' => $newName,
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => $oldName,
        ]);
    }

    /** @test */
    public function it_only_updates_a_user_for_an_authenticated_request()
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/$user->id", [
            'name' => 'New User',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $userToDelete = User::factory()->create();
        $user = User::factory()->create();

        $this->assertDatabaseCount('users', 2);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/users/$userToDelete->id");

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'name' => $userToDelete->name,
        ]);
    }

    /** @test */
    public function it_can_only_delete_a_user_for_an_authenticated_request()
    {
        $userToDelete = User::factory()->create();
        User::factory()->create();

        $this->assertDatabaseCount('users', 2);

        $response = $this->deleteJson("/api/users/$userToDelete->id");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'name' => $userToDelete->name,
        ]);
    }
}
