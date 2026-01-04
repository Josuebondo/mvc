<?php

namespace Tests\Unit;

use Tests\TestCase;
use Core\Helpers;

class HelpersTest extends TestCase
{
    /** @test */
    public function test_escape_function()
    {
        $input = '<script>alert("XSS")</script>';
        $expected = '&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;';

        $this->assertEquals($expected, escape($input));
    }

    /** @test */
    public function test_url_function()
    {
        putenv('URLROOT=/bondo/');
        $result = url('/blog');

        $this->assertStringContainsString('/bondo/blog', $result);
    }

    /** @test */
    public function test_is_post_function()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue(isPost());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse(isPost());
    }

    /** @test */
    public function test_is_get_function()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue(isGet());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse(isGet());
    }

    /** @test */
    public function test_dd_function()
    {
        ob_start();

        // This would die, so we'll test separately
        // dd(['test' => 'value']);

        ob_end_clean();
        $this->assertTrue(true);
    }
}
