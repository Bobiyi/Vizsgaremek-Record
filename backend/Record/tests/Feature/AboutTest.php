<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutTest extends TestCase
{

    public function test_about_status(): void
    {
        $response = $this->get('/api/about');

        $response->assertStatus(200);
    }

    public function test_about_structure(): void
    {
        $response = $this->get('/api/about');

        $response->assertJsonStructure(['appName','authors','gitRepository','version','laravelVersion','phpVersion','timestamp']);
    }
}
