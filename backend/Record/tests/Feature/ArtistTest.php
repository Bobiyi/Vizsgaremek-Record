<?php

namespace Tests\Feature;

use Tests\TestCase;
class ArtistTest extends TestCase
{
    public function test_get_artists_status(): void
    {
        $response = $this->getJson('/api/artists');

        $response->assertStatus(200);
    }

    public function test_get_artists_structure(): void
    {
        $response = $this->getJson('/api/artists');

        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'artistName', 'activeSince', 'nationality', 'website', 'isGroup', 'artistIconPath', 'artistCoverPath']]);
    }

    public function test_artist_endpoint_redirects_to_artists_endpoint(): void
    {
        $response = $this->get('/api/artist');

        $response->assertStatus(301);
        $response->assertRedirect('/api/artists');
    }

    public function test_get_single_artist_structure_when_artist_exists(): void
    {
        $artists = $this->getJson('/api/artists')->json();

        if (empty($artists)) {
            $this->markTestSkipped('No artists available to test /api/artists/{artistId}.');
        }

        $artistId = $artists[0]['id'];
        $response = $this->getJson('/api/artists/' . $artistId);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'artistName', 'activeSince', 'nationality', 'website', 'isGroup', 'artistIconPath', 'artistCoverPath']);
    }

    public function test_get_artist_not_found(): void
    {
        $response = $this->getJson('/api/artists/999999');

        $response->assertStatus(404);
    }

    public function test_get_artists_records_structure_when_artist_exists(): void
    {
        $artists = $this->getJson('/api/artists')->json();

        if (empty($artists)) {
            $this->markTestSkipped('No artists available to test /api/artists-record/{artistId}.');
        }

        $artistId = $artists[0]['id'];
        $response = $this->getJson('/api/artists-record/' . $artistId);

        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'name', 'typeName', 'releaseYear', 'length', 'coverUrl']]);
    }

    public function test_get_artists_records_structure_when_artist_not_exists(): void
    {
        $response = $this->getJson('/api/artists-record/9999');

        $response->assertStatus(404);
    }
}
