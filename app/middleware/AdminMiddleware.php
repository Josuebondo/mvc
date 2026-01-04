<?php

namespace App\Middleware;

use Core\Middleware;

class AdminMiddleware extends Middleware
{
    public function handle(): bool
    {
        // Your logic here
        
        return true;  // Continue
        // return false;  // Stop
    }
}