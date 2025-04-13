<?php

namespace App\Controllers\Restrito;
use App\Models\TbProduto;

class Produto extends \App\Controllers\BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    
    public function index()
    {
        $this->dados['listagem'] = (new TbProduto())->findAll();
        return view('restrito/produto/index');
    }
    
    public function formulario()
    {
        return view('restrito/produto/formulario');
    }

    public function salvar()
    {
        dd($this->request->getPost());

        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   

        }else{

            $rules = [
                'usuario' => 'required|min_length[3]|max_length[255]',
                'senha'   => 'required|min_length[3]|max_length[255]',
            ];

            $messages   = [
                'usuario' => [
                    'required' => 'Campo obrigatório.',
                    'min_length' => 'A quantidade de caracteres informada está menor que o permitido.',
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido.',
                ],
                'senha' => [
                    'required' => 'Campo obrigatório.',
                    'min_length' => 'A quantidade de caracteres informada está menor que o permitido.',
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido.',
                ],
            ];

            
            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            dd('validado', $this->request->getPost());





        }
    }

}
