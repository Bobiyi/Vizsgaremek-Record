<?php

namespace Tests\Feature;

use App\Models\RecordType;
use App\Models\RequestQueue;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QueueTest extends TestCase
{
    public function test_get_requests_requires_authentication(): void
    {
        $response = $this->getJson('/api/requests');

        $response->assertStatus(401);
    }

    public function test_add_queue_request_requires_authentication(): void
    {
        $response = $this->postJson('/api/queue-request', [
            'type' => 'new_record',
            'payload' => json_encode([
                'recordName' => 'Queue Record Unauthenticated',
                'typeId' => 1,
            ]),
        ]);

        $response->assertStatus(401);
    }

    public function test_add_queue_request_new_record_with_auth_returns_201(): void
    {
        $typeId = RecordType::query()->value('id');

        if ($typeId === null) {
            $this->markTestSkipped('No record types available to test /api/queue-request for new_record.');
        }

        $user = User::create([
            'name' => 'queue-user-' . uniqid(),
            'email' => 'queue-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/queue-request', [
            'type' => 'new_record',
            'payload' => json_encode([
                'recordName' => 'Queue Record ' . uniqid(),
                'typeId' => $typeId,
                'releaseYear' => 2020,
                'length' => 10,
            ]),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message', 'id']);
    }

    public function test_get_requests_with_auth_returns_list_structure(): void
    {
        $user = User::create([
            'name' => 'queue-list-user-' . uniqid(),
            'email' => 'queue-list-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'admin',
        ]);

        RequestQueue::create([
            'user_id' => $user->id,
            'type' => 'new_artist',
            'payload' => [
                'artistName' => 'Pending Artist ' . uniqid(),
                'isGroup' => 0,
            ],
            'status' => 'pending',
        ]);

        Sanctum::actingAs($user, ['admin']);

        $response = $this->getJson('/api/requests');

        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'userId', 'userName', 'type', 'data', 'createdAt']]);
    }

    public function test_accept_request_requires_admin_role_with_invalid_token(): void
    {
        $typeId = RecordType::query()->value('id');

        if ($typeId === null) {
            $this->markTestSkipped('No record types available to test /api/request/{id}/accept.');
        }

        $requestUser = User::create([
            'name' => 'queue-request-user-' . uniqid(),
            'email' => 'queue-request-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'user',
        ]);

        $pendingRequest = RequestQueue::create([
            'user_id' => $requestUser->id,
            'type' => 'new_record',
            'payload' => [
                'recordName' => 'Request To Accept ' . uniqid(),
                'typeId' => $typeId,
            ],
            'status' => 'pending',
        ]);

        Sanctum::actingAs($requestUser, ['*']);

        $response = $this->patchJson('/api/request/' . $pendingRequest->id . '/accept', [
            'adminNote' => 'Not allowed for normal user',
        ]);

        $response->assertStatus(403);
    }

    public function test_accept_request_requires_admin_role_with_valid_token(): void
    {
        $typeId = RecordType::query()->value('id');

        if ($typeId === null) {
            $this->markTestSkipped('No record types available to test /api/request/{id}/accept.');
        }

        $requestUser = User::create([
            'name' => 'queue-request-user-' . uniqid(),
            'email' => 'queue-request-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'admin',
        ]);

        $recordName='Request To Accept ' . uniqid();

        $pendingRequest = RequestQueue::create([
            'user_id' => $requestUser->id,
            'type' => 'new_record',
            'payload' => [
                'recordName' => $recordName,
                'typeId' => $typeId,
            ],
            'status' => 'pending',
        ]);

        Sanctum::actingAs($requestUser, ['*']);

        $response = $this->patchJson('/api/request/' . $pendingRequest->id . '/accept', [
            'adminNote' => 'Allowed for Admins.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message'=>'Request accepted!', 'modified'=>"$recordName has been added to the database!"]);
    }

    public function test_reject_request_as_admin_returns_200(): void
    {
        $requestUser = User::create([
            'name' => 'queue-reject-user-' . uniqid(),
            'email' => 'queue-reject-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'user',
        ]);

        $adminUser = User::create([
            'name' => 'queue-admin-' . uniqid(),
            'email' => 'queue-admin-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => (string) random_int(10 ** 7, (10 ** 8) - 1),
            'role' => 'admin',
        ]);

        $pendingRequest = RequestQueue::create([
            'user_id' => $requestUser->id,
            'type' => 'new_artist',
            'payload' => [
                'artistName' => 'Request To Reject ' . uniqid(),
                'isGroup' => 1,
            ],
            'status' => 'pending',
        ]);

        Sanctum::actingAs($adminUser, ['*']);

        $response = $this->patchJson('/api/request/' . $pendingRequest->id . '/reject', [
            'adminNote' => 'Rejected in test',
        ]);

        $response->assertStatus(200);
    }
}
