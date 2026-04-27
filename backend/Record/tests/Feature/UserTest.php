<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->text('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function test_register_status_and_structure(): void
    {
        $suffix = uniqid();
        $response = $this->postJson('/api/user/register', [
            'userName' => 'register-user-' . $suffix,
            'password' => 'Password123!',
            'email' => 'register-' . $suffix . '@example.com',
            'phoneNumber' => (string) random_int(10 ** 7, (10 ** 8) - 1),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'user' => ['id', 'userName', 'email', 'role', 'phoneNumber'],
        ]);
    }

    public function test_login_success_returns_token_and_user(): void
    {
        $suffix = uniqid();
        $user = User::create([
            'name' => 'login-user-' . $suffix,
            'email' => 'login-' . $suffix . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/user/login', [
            'userName' => $user->name,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'token',
            'user' => ['id', 'userName', 'email', 'role', 'phoneNumber'],
        ]);
    }

    public function test_login_with_wrong_password_returns_403(): void
    {
        $suffix = uniqid();
        $user = User::create([
            'name' => 'wrong-pass-user-' . $suffix,
            'email' => 'wrong-pass-' . $suffix . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/user/login', [
            'userName' => $user->name,
            'password' => 'NotTheRightPassword',
        ]);

        $response->assertStatus(403);
    }

    public function test_get_users_requires_authentication(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401);
    }

    public function test_get_users_requires_admin_role(): void
    {
        $user = User::create([
            'name' => 'normal-user-' . uniqid(),
            'email' => 'normal-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/users');

        $response->assertStatus(403);
    }

    public function test_get_users_as_admin_returns_list(): void
    {
        $admin = User::create([
            'name' => 'admin-user-' . uniqid(),
            'email' => 'admin-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'admin',
        ]);

        Sanctum::actingAs($admin, ['*']);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'userName', 'email', 'role', 'phoneNumber']]);
    }

    public function test_logout_with_valid_token_returns_205(): void
    {
        $suffix = uniqid();
        $user = User::create([
            'name' => 'logout-user-' . $suffix,
            'email' => 'logout-' . $suffix . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'user',
        ]);

        $token = $user->createToken('test-token', ['*'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/user/logout');

        $response->assertStatus(205);
    }
}
