<?php
namespace App\Controllers\Restrito;

use App\Controllers\BaseController;

class Entrada extends BaseController
{
    public function index()
    {
        return view('restrito/produto/entrada/index');
    }

    public function formulario()
    {
        return view('restrito/produto/entrada/formulario');
    }

    public function salvar()
    {
        dd($this->request->getPost());
    }
}
