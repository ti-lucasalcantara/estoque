<?php
namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;


class Saida extends BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    
    public function index()
    {
        $this->dados['listagem'] = (new TbProduto())->findAll();
        return view('restrito/produto/saida/index');
    }

    public function formulario()
    {
        return view('restrito/produto/saida/formulario');
    }

    public function salvar()
    {
        dd($this->request->getPost());
    }
}
