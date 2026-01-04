<?php

namespace App\Controllers;

use Core\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $this->view('{strtolower(ProductController)}/index');
    }
}