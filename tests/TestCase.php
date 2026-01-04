<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test base class - extend this for all tests
 */
class TestCase as PHPUnit_TestCase
{
    /**
     * Set up test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Load environment
        $this->loadEnv();
    }

    /**
     * Load .env file for tests
     */
    protected function loadEnv()
    {
        if (file_exists(__DIR__ . '/../.env.testing')) {
            $env = parse_ini_file(__DIR__ . '/../.env.testing');
            foreach ($env as $key => $value) {
                putenv("$key=$value");
            }
        }
    }

    /**
     * Tear down after each test
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
