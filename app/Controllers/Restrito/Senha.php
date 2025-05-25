<?php

namespace App\Controllers\Restrito;

use App\Models\TbUsuario;

class Senha extends \App\Controllers\BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    
    public function index()
    {
        return view('restrito/senha/index', $this->dados);
    }

    public function salvar()
    {

        $rules = [
            'nova_senha' => 'required|min_length[3]|max_length[255]',
            'confirma_senha' => 'required|matches[nova_senha]',
        ];

        $messages   = [
            'nova_senha' => [
                'required' => 'Campo obrigatório.',
                'min_length' => 'A quantidade de caracteres informada está menor que o permitido.',
                'max_length' => 'A quantidade de caracteres informada está maior que o permitido.',
            ],
            'confirma_senha' => [
                'required' => 'Campo obrigatório.',
                'matches' => 'As senhas não conferem.',
            ],
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        if (! $validation->run($this->request->getPost()) ) {
            return redirect()->back()->withInput();   
        }

        $TbUsuario = new TbUsuario();

        $usuario['id_usuario'] = session('usuario_logado')['id_usuario'];
        $usuario['senha']      = password_hash($this->request->getPost('nova_senha'), PASSWORD_DEFAULT);
        
        $save = $TbUsuario->save($usuario);

        if (! $save ){
            session()->setFlashdata( getMessageFail() );
        }else{
            session()->setFlashdata( getMessageSucess() );
        }

        return redirect()->back()->withInput();   
    }


}
