<?php

namespace App\Controllers\Restrito;

use App\Libraries\PoliticaCorrecoes;
use App\Libraries\PermissoesEstoque;

class Config extends \App\Controllers\BaseController
{
    private const TITULOS = [
        'ref_cor' => 'Gerenciar cores',
        'ref_caracteristica' => 'Características de produtos',
        'tb_usuario' => 'Gerenciar Usuários',
        'ref_categoria' => 'Gerenciar categorias',
        'ref_local' => 'Gerenciar Localizações',
        'ref_motivo_entrada' => 'Gerenciar Motivo Entrada',
        'ref_motivo_saida' => 'Gerenciar Motivo Saída',
    ];

    private const CAMPOS_EDITAVEIS = [
        'ref_cor' => ['nome', 'hexadecimal'],
        'ref_caracteristica' => ['caracteristica', 'label'],
        'tb_usuario' => ['nome', 'email', 'cargo'],
        'ref_categoria' => ['categoria'],
        'ref_local' => ['local'],
        'ref_motivo_entrada' => ['motivo_entrada'],
        'ref_motivo_saida' => ['motivo_saida'],
    ];

    private $dados;
    protected $db;

    public function __construct(){
        $this->dados = array();
        $this->db = db_connect();
    }
    
    public function index()
    {
        if (!PermissoesEstoque::podeAbrirConfiguracoes()) {
            return $this->response->setStatusCode(403);
        }
        $this->dados['configs'] = [];
        foreach (self::TITULOS as $tabela => $titulo) {
            if ($this->podeAcessar($tabela, 'ver')) {
                $this->dados['configs'][] = ['titulo' => $titulo, 'parametro' => $tabela, 'rota' => 'restrito.config.crud'];
            }
        }

        return view('restrito/config/index', $this->dados);
    }

    public function crud($titulo, $tabela, $id=null)
    {
        $tabela = base64_decode($tabela, true);
        if (!$this->podeAcessar($tabela, $id === null ? 'ver' : 'editar')) {
            return $this->response->setStatusCode(403);
        }

        $lista = $this->db->table($tabela);
        if ($tabela === 'tb_usuario') {
            $lista->where('deleted_at', null)->orderBy('nome');
        }
        $query = $lista->get();
        $dados = $query->getResultArray();
        $campos = $this->db->getFieldNames($tabela);

        $camposVisiveis = array_filter($campos, function($campo) {
            $excluir = ['json', 'created_at', 'updated_at', 'deleted_at', 'user_created', 'user_updated', 'user_deleted', 'senha', 'cpf', 'avatar', 'ativo', 'permissoes'];
            return !in_array(strtolower($campo), $excluir);
        });

        if ($id && !empty($id)) {
            $id = base64_decode($id);
            $registro = $this->db->table($tabela)->where(getIdNameTabela($tabela), $id);
            if ($tabela === 'tb_usuario') {
                $registro->where('deleted_at', null);
            }
            $item = $registro->get()->getRowArray();
            if (!$item) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        return view('restrito/config/crud', [
            'titulo' => self::TITULOS[$tabela],
            'tabela' => $tabela,
            'dados' => $dados,
            'campos' => $campos,
            'camposVisiveis' => $camposVisiveis,
            'item' => $item ?? [],
            'podeEditar' => $this->podeAcessar($tabela, 'editar'),
            'podeExcluir' => $this->podeAcessar($tabela, 'excluir'),
            'usuariosExcluidos' => $tabela === 'tb_usuario'
                ? $this->db->table('tb_usuario')->select('id_usuario, nome, email, deleted_at')
                    ->where('id_usuario !=', 1)->where('deleted_at IS NOT NULL', null, false)
                    ->orderBy('deleted_at', 'DESC')->get()->getResultArray()
                : [],
            'podeExcluirUsuarioDefinitivamente' => $tabela === 'tb_usuario' && $this->podeExcluirUsuarioDefinitivamente(),
        ]);
    }

    public function correcoes()
    {
        if (!PermissoesEstoque::tem('config.correcoes')) {
            return $this->response->setStatusCode(403);
        }

        return view('restrito/config/correcoes', [
            'somenteAdministrador' => (new PoliticaCorrecoes())->somenteAdministrador(),
        ]);
    }

    public function salvarCorrecoes()
    {
        if (!PermissoesEstoque::tem('config.correcoes')) {
            return $this->response->setStatusCode(403);
        }

        $politica = new PoliticaCorrecoes();
        $valor = $this->request->getPost('somente_administrador');
        $salvou = in_array($valor, ['0', '1'], true)
            && $politica->somenteAdministrador() !== null
            && $politica->salvar($valor === '1');
        session()->setFlashdata($salvou ? getMessageSucess() : getMessageFail());
        return redirect()->route('restrito.config.correcoes')->withCookies();
    }
    

    public function salvar()
    {
        $post = $this->request->getPost();
        
        $tabela = $post['tabela'] ?? false;
        $id     = $post['id'] ?? '';

        if (!$this->podeAcessar($tabela, 'editar')) {
            return $this->response->setStatusCode(403);
        }
        $titulo = self::TITULOS[$tabela];

        $campos = array_intersect_key($post, array_flip(self::CAMPOS_EDITAVEIS[$tabela]));
        if ($tabela === 'tb_usuario') {
            if ((int) $id === 1) {
                return $this->response->setStatusCode(403);
            }
            $senha = $post['senha'] ?? '';
            $this->request->setGlobal('post', array_diff_key($post, ['senha' => true]));
            if ($id === '' && !$this->senhaValida($senha)) {
                session()->setFlashdata(getMessageFail('toast', ['text' => 'Informe uma senha válida com pelo menos 3 caracteres. Se for muito longa, use uma versão mais curta.']));
                return redirect()->back()->withInput()->withCookies();
            }
            if ($id === '') {
                $campos['senha'] = password_hash($senha, PASSWORD_DEFAULT);
            }
            $cargo = $campos['cargo'] ?? null;
            $solicitadas = $post['permissoes'] ?? [];
            if (!in_array($cargo, ['Administrador', 'Usuário'], true) || !is_array($solicitadas)
                || array_filter($solicitadas, static fn ($valor) => !is_string($valor))
                || ((int) $id === (int) session('usuario_logado.id_usuario') && $cargo !== 'Administrador')) {
                session()->setFlashdata(getMessageFail('toast', ['text' => 'Verifique o cargo e as permissões. Seu próprio acesso de administrador deve ser mantido.']));
                return redirect()->back()->withInput()->withCookies();
            }
            $campos['permissoes'] = json_encode(
                $cargo === 'Administrador' ? PermissoesEstoque::permitidas() : array_values(array_intersect(PermissoesEstoque::permitidas(), $solicitadas)),
                JSON_THROW_ON_ERROR
            );
        }
        if ($tabela === 'ref_caracteristica' && !$this->validateData($campos, [
            'caracteristica' => 'required|max_length[255]',
            'label' => 'required|max_length[100]',
        ])) {
            session()->setFlashdata(getMessageFail('toast', ['text' => 'Preencha a característica e o rótulo respeitando os limites dos campos.']));
            return redirect()->back()->withInput()->withCookies();
        }
        if (isset($id) && !empty($id)) {
            $registro = $this->db->table($tabela)->where(getIdNameTabela($tabela), $id);
            if ($tabela === 'tb_usuario') {
                $registro->where('deleted_at', null);
            }
            $save = $registro->update($campos);
        } else {
            $save = $this->db->table($tabela)->insert($campos);
        }

        if(!$save){
            session()->setFlashdata( getMessageFail() );
        }else{
            session()->setFlashdata( getMessageSucess() );
        }

        if ($tabela === 'tb_usuario') {
            return $save
                ? redirect()->route('restrito.config.crud', [base64_encode($titulo), base64_encode($tabela)])->withCookies()
                : redirect()->back()->withInput()->withCookies();
        }
        if (isset($id) && !empty($id)) {
            return redirect()->route('restrito.config.edit', [base64_encode($titulo),base64_encode($tabela),base64_encode($id)])->withCookies();
        }

        return redirect()->route('restrito.config.crud', [base64_encode($titulo),base64_encode($tabela)])->withCookies();
    }

    public function alterarSenhaUsuario()
    {
        if (!$this->podeAcessar('tb_usuario', 'editar')) {
            return $this->response->setStatusCode(403);
        }

        $dados = $this->request->getPost();
        $id = filter_var($dados['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 2]]);
        $senha = $dados['nova_senha'] ?? null;
        $confirmacao = $dados['confirma_senha'] ?? null;
        if ($id === false) {
            session()->setFlashdata(getMessageFail('toast', ['text' => 'Usuário indisponível para alteração de senha.']));
            return $this->voltarAUsuarios();
        }

        if (!$this->senhaValida($senha) || $senha !== $confirmacao) {
            session()->setFlashdata('erro_senha_usuario', [
                'id' => $id,
                'mensagem' => !$this->senhaValida($senha) ? 'Informe uma senha válida com pelo menos 3 caracteres.' : 'As duas senhas precisam ser iguais.',
            ]);
            return $this->voltarAUsuarios();
        }

        $salvou = $this->db->table('tb_usuario')->where('id_usuario', $id)->where('deleted_at', null)
            ->update(['senha' => password_hash($senha, PASSWORD_DEFAULT), 'user_updated' => session('usuario_logado.id_usuario')]);
        session()->setFlashdata($salvou && $this->db->affectedRows()
            ? getMessageSucess('toast', ['title' => 'Senha alterada'])
            : getMessageFail('toast', ['text' => 'Não foi possível alterar a senha deste usuário.']));
        return $this->voltarAUsuarios();
    }

    public function restaurarUsuario()
    {
        if (!$this->podeAcessar('tb_usuario', 'editar')) {
            return $this->response->setStatusCode(403);
        }

        $id = filter_var($this->request->getPost('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 2]]);
        $salvou = $id !== false && $this->db->table('tb_usuario')->where('id_usuario', $id)
            ->where('deleted_at IS NOT NULL', null, false)
            ->update([
                'ativo' => 1,
                'deleted_at' => null,
                'user_deleted' => null,
                'user_updated' => session('usuario_logado.id_usuario'),
            ]);

        session()->setFlashdata($salvou && $this->db->affectedRows()
            ? getMessageSucess('toast', ['title' => 'Usuário restaurado', 'text' => 'A conta voltou a ter acesso ao sistema.'])
            : getMessageFail('toast', ['text' => 'Usuário excluído não encontrado.']));
        return $this->voltarAUsuarios();
    }

    public function excluirUsuarioDefinitivamente()
    {
        if (!$this->podeExcluirUsuarioDefinitivamente()) {
            return $this->response->setJSON(getMessageFail())->setStatusCode(403);
        }
        $id = filter_var($this->request->getPost('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 2]]);
        if ($id === false) {
            return $this->response->setJSON(getMessageFail('toast', ['text' => 'Usuário inválido.']))->setStatusCode(422);
        }

        try {
            $excluiu = $this->db->table('tb_usuario')->where('id_usuario', $id)
                ->where('deleted_at IS NOT NULL', null, false)->delete();
            if (!$excluiu) {
                return $this->falhaExclusao((int) $this->db->error()['code']);
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $erro) {
            return $this->falhaExclusao((int) $erro->getCode());
        }
        if (!$this->db->affectedRows()) {
            return $this->response->setJSON(getMessageFail('toast', [
                'text' => 'A exclusão definitiva só está disponível para contas já excluídas.',
            ]))->setStatusCode(404);
        }

        return $this->response->setJSON(getMessageSucess('toast', ['title' => 'Usuário excluído definitivamente']));
    }

    private function podeExcluirUsuarioDefinitivamente(): bool
    {
        return (int) session('usuario_logado.id_usuario') === 1 && $this->podeAcessar('tb_usuario', 'excluir');
    }

    private function senhaValida($senha): bool
    {
        return is_string($senha) && mb_strlen(trim($senha)) >= 3 && strlen($senha) <= 72 && !str_contains($senha, "\0");
    }

    private function voltarAUsuarios()
    {
        return redirect()->route('restrito.config.crud', [
            base64_encode(self::TITULOS['tb_usuario']), base64_encode('tb_usuario'),
        ])->withCookies();
    }

    public function excluir()
    {
        $tabela = $this->request->getPost('tabela') ?? false;
        if (!$this->podeAcessar($tabela, 'excluir')) {
            return $this->response->setJSON(getMessageFail())->setStatusCode(403);
        }
        $id = filter_var($this->request->getPost('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            return $this->response->setJSON(getMessageFail('toast', ['text' => 'Cadastro inválido.']))->setStatusCode(422);
        }
        if ($tabela === 'tb_usuario' && in_array($id, [1, (int) session('usuario_logado.id_usuario')], true)) {
            return $this->response->setJSON(getMessageFail('toast', ['text' => 'Este usuário está protegido contra exclusão.']))->setStatusCode(403);
        }

        try {
            $registro = $this->db->table($tabela)->where(getIdNameTabela($tabela), $id);
            $excluido = $tabela === 'tb_usuario'
                ? $registro->where('deleted_at', null)->update([
                    'ativo' => 0, 'deleted_at' => date('Y-m-d H:i:s'), 'user_deleted' => session('usuario_logado.id_usuario'),
                ])
                : $registro->delete();
            if (!$excluido) {
                $erro = $this->db->error();
                return $this->falhaExclusao((int) $erro['code']);
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $erro) {
            return $this->falhaExclusao((int) $erro->getCode());
        }

        if (!$this->db->affectedRows()) {
            return $this->response->setJSON(getMessageFail('toast', ['text' => 'Cadastro não encontrado.']))->setStatusCode(404);
        }

        return $this->response->setJSON(getMessageSucess('toast', ['title' => 'Cadastro excluído']));
    }

    private function falhaExclusao(int $codigo)
    {
        if ($codigo === 1451) {
            return $this->response->setJSON(getMessageFail('toast', [
                'text' => 'Este cadastro está vinculado a outros registros e não pode ser excluído.',
            ]))->setStatusCode(409);
        }
        log_message('error', 'Falha na exclusão de cadastro. Código do banco: {codigo}', ['codigo' => $codigo]);
        return $this->response->setJSON(getMessageFail())->setStatusCode(500);
    }
    private function podeAcessar($tabela, string $acao): bool
    {
        return is_string($tabela) && isset(self::CAMPOS_EDITAVEIS[$tabela])
            && PermissoesEstoque::cadastro($tabela, $acao);
    }

}
