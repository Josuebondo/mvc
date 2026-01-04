<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/index', [
            'title' => 'BondoMVC - Framework PHP MVC Léger & Performant',
            'version' => '1.1.0'
        ]);
    }
}
