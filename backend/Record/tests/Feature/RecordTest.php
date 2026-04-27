<?php

namespace Tests\Feature;

use App\Models\RecordType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RecordTest extends TestCase
{
    public function test_get_records_status(): void
    {
        $response = $this->getJson('/api/records');

        $response->assertStatus(200);
    }

    public function test_get_records_structure(): void
    {
        $response = $this->getJson('/api/records');

        $response->assertJsonStructure(['*'=>['id','name','typeName','releaseYear','length','coverUrl','artistName']]);
    }

    public function test_get_records_items_have_expected_value_types(): void
    {
        $response = $this->getJson('/api/records');

        $records = $response->json();

        if (empty($records)) {
            $this->markTestSkipped('No records available to validate item value types.');
        }

        $first = $records[0];

        $this->assertIsInt($first['id']);
        $this->assertIsString($first['name']);
        $this->assertTrue(is_string($first['typeName']) || is_null($first['typeName']));
        $this->assertTrue(is_numeric($first['releaseYear']) || is_null($first['releaseYear']));
        $this->assertTrue(is_numeric($first['length']) || is_null($first['length']));
        $this->assertTrue(is_string($first['coverUrl']) || is_null($first['coverUrl']));
        $this->assertIsArray($first['artistName']);
    }

    public function test_record_endpoint_redirects_to_records_endpoint(): void
    {
        $response = $this->get('/api/record');

        $response->assertStatus(301);
        $response->assertRedirect('/api/records');
    }

    public function test_get_single_record_structure_when_record_exists(): void
    {
        $recordsResponse = $this->getJson('/api/records');
        $records = $recordsResponse->json();

        if (empty($records)) {
            $this->markTestSkipped('No records available to test /api/records/{id}.');
        }

        $recordId = $records[0]['id'];
        $response = $this->get('/api/records/' . $recordId);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id','name','typeName','releaseYear','length','coverUrl','artistName']);
    }

    public function test_get_record_not_found(): void
    {
        $response = $this->getJson('/api/records/9999');

        $response->assertStatus(404);
    }

    public function test_get_record_types_status_and_structure(): void
    {
        $response = $this->getJson('/api/records/types');

        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'typeName']]);
    }

    public function test_get_records_by_type_structure_when_type_exists(): void
    {
        $typeName = RecordType::query()->value('type_name');

        if ($typeName === null) {
            $this->markTestSkipped('No record types available to test /api/records/types/{typeName}.');
        }

        $response = $this->getJson('/api/records/types/' . $typeName);

        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'name', 'typeName', 'releaseYear', 'length', 'coverUrl', 'artistName']]);
    }

    public function test_get_records_artists_structure_when_record_exists(): void
    {
        $records = $this->getJson('/api/records')->json();

        if (empty($records)) {
            $this->markTestSkipped('No records available to test /api/records-artist/{recordId}.');
        }

        $recordId = $records[0]['id'];
        $response = $this->getJson('/api/records-artist/' . $recordId);

        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'artistName', 'activeSince', 'nationality', 'website', 'isGroup', 'artistIconPath', 'artistCoverPath']]);
    }

    public function test_favourite_requires_authentication(): void
    {
        $response = $this->postJson('/api/favourite', ['recordId' => 1]);

        $response->assertStatus(401);
    }

    public function test_favourite_can_be_toggled_with_token(): void
    {
        $records = $this->getJson('/api/records')->json();

        if (empty($records)) {
            $this->markTestSkipped('No records available to test /api/favourite.');
        }

        $user = User::create([
            'name' => 'test-user-' . uniqid(),
            'email' => 'test-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => random_int(10 ** (8 - 1), (10 ** 8) - 1),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user, ['*']);

        $recordId = $records[0]['id'];

        $firstResponse = $this->postJson('/api/favourite', ['recordId' => $recordId]);
        $firstResponse->assertStatus(201);

        $secondResponse = $this->postJson('/api/favourite', ['recordId' => $recordId]);
        $secondResponse->assertStatus(200);
    }

    public function test_get_favourites_by_user_id_structure_when_user_has_favourites(): void
    {
        $records = $this->getJson('/api/records')->json();

        if (empty($records)) {
            $this->markTestSkipped('No records available to test /api/favourite endpoints.');
        }

        $user = User::create([
            'name' => 'test-user-' . uniqid(),
            'email' => 'test-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => random_int(10 ** (8 - 1), (10 ** 8) - 1),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user, ['*']);

        $recordId = $records[0]['id'];
        $this->postJson('/api/favourite', ['recordId' => $recordId])->assertStatus(201);

        $response = $this->getJson('/api/favourite/' . $user->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['recordId']
            ])
            ->assertJsonFragment(['recordId' => $recordId]);
    }

    public function test_get_favourites_list_by_user_id_structure_when_user_has_favourites(): void
    {
        $records = $this->getJson('/api/records')->json();

        if (empty($records)) {
            $this->markTestSkipped('No records available to test /api/favourite endpoints.');
        }

        $user = User::create([
            'name' => 'test-user-' . uniqid(),
            'email' => 'test-' . uniqid() . '@example.com',
            'password_hash' => Hash::make('Password123!'),
            'phone' => random_int(10 ** (8 - 1), (10 ** 8) - 1),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user, ['*']);

        $recordId = $records[0]['id'];
        $this->postJson('/api/favourite', ['recordId' => $recordId])->assertStatus(201);

        $response = $this->getJson('/api/favourite/' . $user->id . '/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'typeName', 'releaseYear', 'length', 'coverUrl']
            ])
            ->assertJsonFragment(['id' => $recordId]);
    }
}
