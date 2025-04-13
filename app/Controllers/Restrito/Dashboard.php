<?php

namespace App\Controllers\Restrito;

class Dashboard extends \App\Controllers\BaseController
{
    public function index()
    {
        return view('restrito/dashboard/index');
    }
}
