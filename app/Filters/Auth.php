<?php

namespace App\Filters;

use App\Libraries\AcessoLembrado;
use App\Libraries\PermissoesEstoque;
use App\Models\TbUsuario;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $usuario_logado = session('usuario_logado');
        $usuario = null;
        if (empty($usuario_logado)) {
            $usuario = AcessoLembrado::restaurar($request);
            $usuario_logado = session('usuario_logado');
        }
        if ( empty($usuario_logado) ){
            session()->setFlashdata( getMessageFail('toast', ['title' => 'Acesso negado!', 'text' => 'Entre com seu usuário e senha']) );
            return redirect()->route('login');
        }
        $usuario ??= (new TbUsuario())->find($usuario_logado['id_usuario']);
        if (!$usuario || !$usuario['ativo']) {
            session()->remove('usuario_logado');
            session()->setFlashdata( getMessageFail('toast', ['title' => 'Acesso negado!', 'text' => 'Usuário sem acesso para acessar o sistema']) );
            return redirect()->route('login');
        }
        session()->set('usuario_logado', array_replace($usuario_logado, array_intersect_key(
            $usuario, array_flip(['nome', 'email', 'cargo', 'ativo', 'permissoes'])
        )));

        $router = service('router');
        $controller = $router->controllerName();
        if (!is_string($controller) || !str_starts_with(ltrim($controller, '\\'), 'App\\Controllers\\Restrito\\')) {
            return null;
        }

        $modulo = strtolower(substr($controller, strrpos($controller, '\\') + 1));
        $metodo = $router->methodName();
        $permissao = match ($modulo) {
            'dashboard', 'relatorio' => $modulo . '.ver',
            'refcategoria' => 'config.ref_categoria.editar',
            'produto', 'entrada', 'saida' => $modulo . '.' . match ($metodo) {
                'index' => 'ver',
                'excluir' => 'excluir',
                'definirCapa' => 'editar',
                'formulario' => isset($router->params()[$modulo === 'produto' ? 0 : 1]) ? 'editar' : 'criar',
                'salvar' => $request->getPost('id_produto' . ($modulo === 'produto' ? '' : '_' . $modulo)) ? 'editar' : 'criar',
                default => 'criar',
            },
            default => null,
        };

        if ($permissao === null || PermissoesEstoque::tem($permissao)) {
            return null;
        }
        if ($modulo === 'dashboard') {
            return redirect()->route(PermissoesEstoque::destinoInicial());
        }
        if ($request->isAJAX()) {
            return service('response')->setStatusCode(403)->setJSON(['text' => 'Seu usuário não tem permissão para esta ação.']);
        }
        return service('response')->setStatusCode(403)->setBody(view('errors/html/error_403'));
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
