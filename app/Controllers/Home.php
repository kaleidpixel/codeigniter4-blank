<?php

namespace App\Controllers;

use CodeIgniter\CodeIgniter;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'version' => CodeIgniter::CI_VERSION,
            'now_year' => date('Y'),
            'ENVIRONMENT' => ENVIRONMENT
        ];

        return view('welcome_message', $data);
    }
}
