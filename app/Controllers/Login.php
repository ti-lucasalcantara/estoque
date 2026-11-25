<?php

namespace App\Controllers;

use App\Libraries\AcessoLembrado;
use App\Models\TbUsuario;

class Login extends BaseController
{
    public function index()
    {
        if (session('usuario_logado') || AcessoLembrado::restaurar($this->request)) {
            return redirect()->route('restrito.dashboard.index');
        }

        return view('login');
    }

    public function entrar()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $lembrar = $this->request->getPost('lembrar') === '1';
        $dados = [
            'email' => is_string($email) ? $email : '',
            'lembrar' => $lembrar,
        ];
        $validation = service('validation');
        $validation->setRules([
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[3]|max_length[255]',
        ], [
            'email' => [
                'required' => 'Campo obrigatório.',
                'valid_email' => 'Informe um email válido.',
            ],
            'senha' => [
                'required' => 'Campo obrigatório.',
                'min_length' => 'Informe pelo menos 3 caracteres.',
                'max_length' => 'Informe até 255 caracteres.',
            ],
        ]);

        $voltar = redirect()->route('login')->withCookies();
        if (!$validation->run($this->request->getPost()) || !is_string($email) || !is_string($senha)) {
            return $voltar->with('login', $dados + ['erros' => $validation->getErrors()]);
        }

        $usuario = (new TbUsuario())->where('email', $email)->first();
        if (!$usuario || !$usuario['ativo'] || !password_verify($senha, $usuario['senha'])) {
            session()->setFlashdata(getMessageFail('toast', [
                'title' => 'Falha no login',
                'text' => 'Usuário e/ou senha inválida.',
            ]));
            return $voltar->with('login', $dados);
        }

        $response = redirect()->route('restrito.dashboard.index')->withCookies();
        AcessoLembrado::revogar($this->request, $response);
        if ($lembrar) {
            AcessoLembrado::lembrar($this->request, $response, $usuario);
        }
        AcessoLembrado::iniciarSessao($usuario);

        return $response;
    }

    public function sair()
    {
        $response = redirect()->route('login')->withCookies();
        AcessoLembrado::revogar($this->request, $response);
        session()->destroy();
        return $response;
    }
}
