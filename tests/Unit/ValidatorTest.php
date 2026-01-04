<?php

namespace Tests\Unit;

use Tests\TestCase;
use Core\Validator;

class ValidatorTest extends TestCase
{
    /** @test */
    public function test_required_rule()
    {
        $validator = new Validator([
            'name' => ''
        ], [
            'name' => 'required'
        ]);

        $this->assertTrue($validator->hasErrors());
    }

    /** @test */
    public function test_email_rule()
    {
        $validator = new Validator([
            'email' => 'invalid-email'
        ], [
            'email' => 'email'
        ]);

        $this->assertTrue($validator->hasErrors());

        $validator2 = new Validator([
            'email' => 'test@example.com'
        ], [
            'email' => 'email'
        ]);

        $this->assertFalse($validator2->hasErrors());
    }

    /** @test */
    public function test_min_rule()
    {
        $validator = new Validator([
            'password' => '123'
        ], [
            'password' => 'min:6'
        ]);

        $this->assertTrue($validator->hasErrors());
    }

    /** @test */
    public function test_max_rule()
    {
        $validator = new Validator([
            'name' => 'This is a very long string that exceeds 10 characters'
        ], [
            'name' => 'max:10'
        ]);

        $this->assertTrue($validator->hasErrors());
    }

    /** @test */
    public function test_numeric_rule()
    {
        $validator = new Validator([
            'age' => 'not-a-number'
        ], [
            'age' => 'numeric'
        ]);

        $this->assertTrue($validator->hasErrors());

        $validator2 = new Validator([
            'age' => '25'
        ], [
            'age' => 'numeric'
        ]);

        $this->assertFalse($validator2->hasErrors());
    }
}
