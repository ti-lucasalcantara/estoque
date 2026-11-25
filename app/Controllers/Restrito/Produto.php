<?php

namespace App\Controllers\Restrito;
use App\Libraries\GaleriaProduto;
use App\Models\TbProduto;
use App\Models\RefCor;
use App\Models\RefCategoria;
use App\Models\TbProdutoImagem;
use App\Models\RefCaracteristica;

class Produto extends \App\Controllers\BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    
    public function index()
    {
        $produtos = (new TbProduto())
            ->select('tb_produto.*, ref_categoria.categoria, ref_cor.nome AS cor_nome, ref_cor.hexadecimal AS cor_hex, fn_saldo_estoque(tb_produto.id_produto) AS saldoEstoque')
            ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
            ->join('ref_cor', 'ref_cor.id_cor = tb_produto.id_cor AND ref_cor.deleted_at IS NULL', 'left')
            ->orderBy('tb_produto.nome', 'ASC')
            ->findAll();

        $fotosPorProduto = [];
        if ($produtos) {
            $fotos = (new TbProdutoImagem())
                ->select('id_produto_imagem, id_produto, imagem, json')
                ->whereIn('id_produto', array_column($produtos, 'id_produto'))
                ->orderBy('principal', 'DESC')
                ->orderBy('id_produto_imagem', 'ASC')
                ->findAll();
            foreach ($fotos as $foto) {
                $fotosPorProduto[$foto['id_produto']][] = $foto;
            }
        }
        $this->dados['tb_produto'] = $produtos;
        $this->dados['fotosPorProduto'] = $fotosPorProduto;
        return view('restrito/produto/index', $this->dados);
    }
    
    public function formulario($id_produto=null)
    {
        if ($id_produto !== null) {
            $id = base64_decode($id_produto, true);
            $this->dados['produto'] = ctype_digit((string) $id) ? (new TbProduto())->find($id) : null;
            if (!$this->dados['produto']) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $this->dados['fotos'] = (new TbProdutoImagem())->where('id_produto', $id)
                ->orderBy('principal', 'DESC')->orderBy('id_produto_imagem')->findAll();
        }

        $this->dados['ref_categoria'] = (new RefCategoria())->orderBy('categoria', 'ASC')->findAll();

        $this->dados['ref_cor'] = (new RefCor())->orderBy('nome', 'ASC')->findAll();

        $this->dados['ref_caracteristica'] = (new RefCaracteristica())->orderBy('label', 'ASC')->findAll();

        return view('restrito/produto/formulario', $this->dados);
    }

    public function salvar()
    {
        $post = $this->request->getPost();
        $rules = [
            'nome' => 'required|max_length[255]',
            'codigo' => 'required|max_length[100]',
            'id_categoria' => 'required|is_natural_no_zero',
            'estoque_minimo' => 'required|is_natural|less_than_equal_to[2147483647]',
            'descricao' => 'permit_empty|max_length[500]',
        ];
        if (!$this->validateData($post, $rules, [
            'nome' => ['required' => 'Informe o nome do produto.'],
            'codigo' => ['required' => 'Informe o código do produto.'],
            'id_categoria' => ['required' => 'Selecione uma categoria.'],
            'estoque_minimo' => [
                'required' => 'Informe o estoque mínimo.',
                'is_natural' => 'Informe uma quantidade inteira igual ou maior que zero.',
            ],
        ])) {
            return redirect()->back()->withInput();
        }

        $edicao = !empty($post['id_produto']);
        $id = $edicao && is_string($post['id_produto']) ? base64_decode($post['id_produto'], true) : null;
        $db = db_connect();
        $arquivosGravados = [];
        $transacao = false;

        try {
            if ($edicao && (!ctype_digit((string) $id) || (int) $id < 1)) {
                throw new \DomainException('Produto inválido.');
            }

            $cores = [];
            foreach ((array) ($post['id_cor'] ?? []) as $cor) {
                // Formulários antigos enviavam o identificador acompanhado do hexadecimal.
                $valor = is_scalar($cor) ? explode('||', (string) $cor, 2)[0] : '';
                if ($valor !== '') {
                    $cores[] = filter_var($valor, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                }
            }
            $cores = array_values(array_unique($cores, SORT_REGULAR));
            if (!$cores || in_array(false, $cores, true) || ($edicao && count($cores) !== 1)
                || (new RefCor())->whereIn('id_cor', $cores)->countAllResults() !== count($cores)) {
                throw new \DomainException($edicao
                    ? 'Selecione uma cor cadastrada para o produto.'
                    : 'Selecione uma ou mais cores cadastradas para o produto.');
            }
            if (!(new RefCategoria())->find($post['id_categoria'])) {
                throw new \DomainException('Selecione uma categoria cadastrada.');
            }

            $uploads = $this->request->getFileMultiple('imagens') ?? [];
            if (!$uploads && ($antiga = $this->request->getFile('imagem'))) {
                $uploads = [$antiga];
            }
            $fotos = GaleriaProduto::preparar($uploads);
            $galeria = json_decode($post['galeria'] ?? '{}', true);
            if (!is_array($galeria)) {
                throw new \DomainException('Não foi possível ler a galeria de fotos.');
            }
            if (isset($galeria['novas']) && count((array) $galeria['novas']) !== count($fotos)) {
                throw new \DomainException('Nem todas as fotos chegaram ao servidor. Envie um lote menor.');
            }

            if (!$db->transBegin()) {
                throw new \RuntimeException('Não foi possível iniciar a gravação.');
            }
            $transacao = true;
            $model = new TbProduto($db);
            if ($edicao) {
                $tabela = $db->protectIdentifiers('tb_produto', true);
                $anterior = $db->query(
                    "SELECT json FROM {$tabela} WHERE id_produto = ? AND deleted_at IS NULL FOR UPDATE",
                    [$id]
                )->getRowArray();
                if (!$anterior) {
                    throw new \DomainException('Produto não encontrado.');
                }
            }

            $produto = array_intersect_key($post, $rules);
            $atributos = $post['caracteristicas'] ?? [];
            if (isset($post['editar_caracteristicas']) || $atributos) {
                if (!is_array($atributos)) {
                    throw new \DomainException('Confira as características selecionadas.');
                }
                $extras = (array) json_decode($anterior['json'] ?? '{}', true);
                $valores = (array) ($extras['caracteristicas'] ?? []);
                $definicoes = array_column((new RefCaracteristica())->findAll(), null, 'id_caracteristica');
                if (isset($post['editar_caracteristicas'])) {
                    $valores = array_diff_key($valores, $definicoes);
                }
                foreach (array_intersect_key($atributos, $definicoes) as $chave => $valor) {
                    if (!is_string($valor) || !mb_check_encoding($valor, 'UTF-8') || mb_strlen(trim($valor)) > 255) {
                        throw new \DomainException('Cada característica aceita até 255 caracteres.');
                    }
                    if (trim($valor) !== '') {
                        $valores[$chave] = trim($valor);
                    }
                }
                $extras['caracteristicas'] = $valores;
                $produto['json'] = json_encode($extras, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            }

            $urls = GaleriaProduto::armazenar($fotos, $arquivosGravados);
            foreach ($cores as $cor) {
                $dados = $produto + ['id_cor' => $cor];
                if ($edicao) {
                    $dados['id_produto'] = $id;
                }
                if (!$model->save($dados)) {
                    throw new \RuntimeException('Não foi possível gravar o produto.');
                }
                $idSalvo = $edicao ? (int) $id : (int) $model->getInsertID();
                if ($urls || $galeria) {
                    GaleriaProduto::salvar($db, $idSalvo, $urls, $galeria);
                }
            }

            if (!$db->transStatus() || !$db->transCommit()) {
                throw new \RuntimeException('Não foi possível concluir a gravação.');
            }
        } catch (\Throwable $erro) {
            if ($transacao) {
                $db->transRollback();
            }
            foreach ($arquivosGravados as $caminho) {
                if (is_file($caminho)) {
                    unlink($caminho);
                }
            }
            $mensagem = $erro instanceof \DomainException ? $erro->getMessage() : 'Não foi possível salvar o produto.';
            if (!$erro instanceof \DomainException) {
                log_message('error', 'Falha no cadastro de produto: {exception}', ['exception' => $erro]);
            }
            session()->setFlashdata(getMessageFail('toast', ['text' => $mensagem]));
            return redirect()->back()->withInput();
        }

        session()->setFlashdata(count($cores) > 1
            ? getMessageSucess('toast', ['text' => count($cores) . ' produtos cadastrados, um para cada cor selecionada.'])
            : getMessageSucess());
        if ($edicao) {
            return redirect()->route('restrito.produto.editar', [base64_encode((string) $id)]);
        }
        return redirect()->route(\App\Libraries\PermissoesEstoque::tem('produto.ver')
            ? 'restrito.produto.index' : 'restrito.produto.formulario');
    }


    public function definirCapa()
    {
        $produto = filter_var($this->request->getPost('produto'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $foto = filter_var($this->request->getPost('foto'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $token = ['token' => csrf_hash()];
        if ($produto === false || $foto === false) {
            return $this->response->setStatusCode(422)->setJSON($token + ['text' => 'Selecione uma foto válida.']);
        }

        $db = db_connect();
        if (!$db->transBegin()) {
            return $this->response->setStatusCode(500)->setJSON($token + ['text' => 'Não foi possível salvar a capa.']);
        }
        try {
            $tabela = $db->protectIdentifiers('tb_produto', true);
            $registro = $db->query(
                "SELECT id_produto FROM {$tabela} WHERE id_produto = ? AND deleted_at IS NULL FOR UPDATE",
                [$produto]
            )->getRow();
            if (!$registro) {
                throw new \DomainException('Produto não encontrado.');
            }
            GaleriaProduto::definirCapa($db, $produto, $foto);
            if (!$db->transStatus() || !$db->transCommit()) {
                throw new \RuntimeException('Não foi possível concluir a troca de capa.');
            }
        } catch (\DomainException $erro) {
            $db->transRollback();
            return $this->response->setStatusCode(404)->setJSON($token + ['text' => $erro->getMessage()]);
        } catch (\Throwable $erro) {
            $db->transRollback();
            log_message('error', 'Falha ao salvar capa do produto: {exception}', ['exception' => $erro]);
            return $this->response->setStatusCode(500)->setJSON($token + ['text' => 'Não foi possível salvar a capa.']);
        }

        return $this->response->setJSON($token + ['text' => 'Foto de capa atualizada.']);
    }

    public function excluir()
    {
        if ( ! $this->request->is('post') ) {
            return $this->response->setJSON( getMessageFail() )->setStatusCode(401);
        }else{
            if(! (new TbProduto())->delete($this->request->getPost('id')) ){
                return $this->response->setJSON( getMessageFail() )->setStatusCode(401);
            }else{
                return $this->response->setJSON( getMessageSucess() )->setStatusCode(200);
            }
        }
    }

}
