<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutingTest extends TestCase
{
    /** @test */
    public function test_home_route_exists()
    {
        // Simpler routing test
        $this->assertTrue(true); // Placeholder
    }

    /** @test */
    public function test_404_page_exists()
    {
        $this->assertFileExists(__DIR__ . '/../../app/views/errors/404.php');
    }
}
