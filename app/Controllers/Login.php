<?php

namespace App\Controllers;

use \App\Models\TbUsuario;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function entrar() {
        
        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   

        }else{

            $rules = [
                'email' => 'required|valid_email',
                'senha' => 'required|min_length[3]|max_length[255]',
            ];

            $messages   = [
                'email' => [
                    'required' => 'Campo obrigatório.',
                    'valid_email' => 'Informe um email válido.',
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
            

            $TbUsuario = new TbUsuario();

            $usuario = $TbUsuario->where('email', $this->request->getPost('email'))->first();

            if(is_null($usuario)){
                session()->setFlashdata( getMessageFail('toast', ['title' => 'Falha no login', 'text' => 'Usuário e/ou senha inválida.']) );
                return redirect()->back()->withInput();   
            }

            if (! password_verify($this->request->getPost('senha'), $usuario['senha']) ){
                session()->setFlashdata( getMessageFail('toast', ['title' => 'Falha no login', 'text' => 'Usuário e/ou senha inválida.']) );
                return redirect()->back()->withInput();   
            }

            // Criar sessão de LOGIN
            $session = [
                'usuario_logado'  => [
                    'id_usuario'  => $usuario['id_usuario'],
                    'nome'        => $usuario['nome'],
                    'email'       => $usuario['email'],
                    'cpf'         => $usuario['cpf'],
                    'cargo'       => $usuario['cargo'],
                    'ativo'       => $usuario['ativo'],
                    'avatar'      => $usuario['avatar'],
                ]
            ]; 

            session()->set($session); 
            return redirect()->route('restrito.dashboard.index');
        }
    }

    public function sair() {
        session()->destroy();
        return redirect()->route('login');
    }
    
}
