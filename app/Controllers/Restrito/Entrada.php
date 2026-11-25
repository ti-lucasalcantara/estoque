<?php
namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;
use App\Models\TbProdutoEntrada;
use App\Models\RefLocal;
use App\Models\RefMotivoEntrada;

class Entrada extends BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    
    public function index()
    {
        return view('restrito/produto/entrada/index', \App\Libraries\HistoricoMovimentacoes::consultar(
            'entrada',
            $this->request->getGet('data_inicio'),
            $this->request->getGet('data_fim')
        ));
    }

    public function formulario($id_produto=null, $id_produto_entrada=null)
    {
        if ($id_produto_entrada !== null && !(new \App\Libraries\PoliticaCorrecoes())->permiteCorrigir()) {
            return $this->response->setStatusCode(403)->setBody('Somente administradores podem corrigir movimentações.');
        }
        if($id_produto){
            $id_produto = base64_decode($id_produto);
            $this->dados['produto'] = (new TbProduto())
                                    ->select('tb_produto.*, ref_categoria.categoria, fn_saldo_estoque(tb_produto.id_produto) AS saldoEstoque')
                                    ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                    ->find($id_produto);

            if (!$this->dados['produto']) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $this->dados['produto']['cor_nome'] = getNomeCorProduto($this->dados['produto'] );
            $this->dados['produto']['cor_hexa'] = getHexaCorProduto($this->dados['produto'] );
        }

        if($id_produto_entrada){
            $id_produto_entrada = base64_decode($id_produto_entrada);
            $this->dados['produto_entrada'] = (new TbProdutoEntrada())->find($id_produto_entrada);
            $registro = $this->dados['produto_entrada'];
            if (!$registro || (int) $registro['id_produto'] !== (int) $id_produto) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $this->dados['ref_local']           = (new RefLocal())->orderBy('local', 'ASC')->findAll();
        $this->dados['ref_motivo_entrada']  = (new RefMotivoEntrada())->orderBy('motivo_entrada', 'ASC')->findAll();
        $this->dados['entradas'] = [];
        if (\App\Libraries\PermissoesEstoque::tem('entrada.ver')) {
            $this->dados['entradas']  = (new TbProdutoEntrada())
                                    ->select('tb_produto_entrada.*, ref_local.local as "local", tb_usuario.nome AS "usuarioCriacao", ref_motivo_entrada.motivo_entrada ')
                                    ->join('tb_usuario', 'tb_usuario.id_usuario=tb_produto_entrada.user_created','left')
                                    ->join('ref_motivo_entrada', 'ref_motivo_entrada.id_motivo_entrada=tb_produto_entrada.id_motivo_entrada', 'left')
                                    ->join('ref_local', 'ref_local.id_local=tb_produto_entrada.id_local', 'left')
                                    ->where('id_produto', $id_produto)
                                    ->orderBy('data_entrada','DESC')
                                    ->findAll();
        }

        return view('restrito/produto/entrada/formulario', $this->dados);
    }

    public function salvar()
    {
        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   
        }else{

            $edit = false;
            $id_produto_entrada = $this->request->getPost('id_produto_entrada') ?? '';
            if(isset($id_produto_entrada) && !empty($id_produto_entrada)){
                $id_produto_entrada = base64_decode($id_produto_entrada);
                $edit = true;
                if (!(new \App\Libraries\PoliticaCorrecoes())->permiteCorrigir()) {
                    return $this->response->setStatusCode(403)->setBody('Somente administradores podem corrigir movimentações.');
                }
            }

            $rules = [
                'data_entrada'  => 'required|valid_date',
                'quantidade'    => 'required|is_natural_no_zero',
                'observacoes'   => 'permit_empty|max_length[500]',
                'id_local'      => 'required',
                'id_motivo_entrada'  => 'required',
            ];

            $messages   = [
                'data_entrada' => [
                    'required' => 'Campo obrigatório.',
                    'valid_date' => 'Data inválida, verifique o campo informado.',
                ],
                'quantidade' => [
                    'required' => 'Campo obrigatório.',
                    'is_natural_no_zero' => 'Campo inválido, verifique o campo informado.',
                ],
               'observacoes' => [
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido, máximo 500 caracteres.',
                ],
                'id_local' => [
                    'required' => 'Campo obrigatório.',
                ],
                'id_motivo_entrada' => [
                    'required' => 'Campo obrigatório.',
                ],
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            $TbProdutoEntrada = new TbProdutoEntrada();
            
            $id_produto   = $this->request->getPost('id_produto');
            $quantidade   = $this->request->getPost('quantidade');
            $data_entrada = $this->request->getPost('data_entrada');
            $observacoes  = $this->request->getPost('observacoes');
            $id_local     = $this->request->getPost('id_local');
            $id_motivo_entrada = $this->request->getPost('id_motivo_entrada');

            $entrada = [
                'id_produto'   => $id_produto,
                'data_entrada' => $data_entrada,
                'quantidade'   => $quantidade,
                'observacoes'  => $observacoes,
                'id_local'     => $id_local,
                'id_motivo_entrada' => $id_motivo_entrada,
            ];

            if($edit){
                $entrada['id_produto_entrada'] = $id_produto_entrada;
            }

            $save = $TbProdutoEntrada->save($entrada);

            if(!$save){
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }

            session()->setFlashdata(getMessageSucess());

            if($edit){
                return redirect()->route('restrito.entrada.editar', [base64_encode($id_produto), base64_encode($id_produto_entrada)]);
            }
            return redirect()->route('restrito.entrada.formulario', [base64_encode($id_produto)]);
        }
    }


    public function formularioMultiplas()
    {
        $this->dados['produtos'] = (new TbProduto())
                                    ->select('tb_produto.id_produto, tb_produto.nome, tb_produto.codigo, ref_cor.nome AS cor_nome, ref_cor.hexadecimal AS cor_hex')
                                    ->join('ref_cor', 'ref_cor.id_cor = tb_produto.id_cor AND ref_cor.deleted_at IS NULL', 'left')
                                    ->orderBy('tb_produto.nome')
                                    ->orderBy('ref_cor.nome')
                                    ->findAll();

        $this->dados['ref_motivo_entrada']  = (new RefMotivoEntrada())->orderBy('motivo_entrada', 'ASC')->findAll();
        $this->dados['ref_local']           = (new RefLocal())->orderBy('local', 'ASC')->findAll();

        return view('restrito/produto/entrada/formulario_multiplas', $this->dados);
    }

    public function salvarMultiplas()
    {
        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   
        }else{

            $rules = [
                'data_entrada'  => 'required|valid_date',
                'observacoes'   => 'permit_empty|max_length[500]',
                'id_local'      => 'required',
                'id_motivo_entrada'  => 'required',
            ];

            $messages   = [
                'data_entrada' => [
                    'required' => 'Campo obrigatório.',
                    'valid_date' => 'Data inválida, verifique o campo informado.',
                ],
               'observacoes' => [
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido, máximo 500 caracteres.',
                ],
                'id_local' => [
                    'required' => 'Campo obrigatório.',
                ],
                'id_motivo_entrada' => [
                    'required' => 'Campo obrigatório.',
                ],
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            $entradas = [];
            $produtos = $this->request->getPost('produtos');
            $dadosComuns = [
                'data_entrada' => $this->request->getPost('data_entrada'),
                'observacoes' => $this->request->getPost('observacoes'),
                'id_local' => $this->request->getPost('id_local'),
                'id_motivo_entrada' => $this->request->getPost('id_motivo_entrada'),
                'user_created' => session('usuario_logado.id_usuario'),
            ];

            foreach ((array) $produtos as $produto) {
                $ids = (array) ($produto['id_produto'] ?? []);
                $quantidade = filter_var($produto['quantidade'] ?? null, FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 1, 'max_range' => 2147483647],
                ]);
                if (!$ids || $quantidade === false) {
                    session()->setFlashdata(getMessageFail('sweetalert', ['text' => 'Informe o produto e uma quantidade inteira positiva em cada linha.']));
                    return redirect()->back()->withInput();
                }
                foreach ($ids as $idProduto) {
                    if (filter_var($idProduto, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
                        session()->setFlashdata(getMessageFail('sweetalert', ['text' => 'Selecione um produto válido em cada linha.']));
                        return redirect()->back()->withInput();
                    }
                    $entradas[] = $dadosComuns + ['id_produto' => $idProduto, 'quantidade' => $quantidade];
                }
            }

            $db = db_connect();
            if (!$entradas || !$db->transBegin()) {
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }

            try {
                $model = new TbProdutoEntrada($db);
                $ids = array_unique(array_column($entradas, 'id_produto'));
                $totalProdutos = (new TbProduto($db))->whereIn('id_produto', $ids)->countAllResults();
                if ($totalProdutos !== count($ids) || !$model->insertBatch($entradas) || !$db->transStatus() || !$db->transCommit()) {
                    throw new \RuntimeException('Não foi possível concluir a entrada em lote.');
                }
            } catch (\Throwable $erro) {
                $db->transRollback();
                log_message('error', 'Falha ao gravar entrada: {exception}', ['exception' => $erro]);
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }
            session()->setFlashdata(getMessageSucess());
            return redirect()->route(\App\Libraries\PermissoesEstoque::tem('entrada.ver')
                ? 'restrito.entrada.index' : 'restrito.entrada.formularioMultiplas');
        }
    }
        
    public function excluir() 
    {
        if (!(new \App\Libraries\PoliticaCorrecoes())->permiteCorrigir()) {
            return $this->response->setJSON(getMessageFail('toast', [
                'text' => 'Somente administradores podem excluir movimentações.',
            ]))->setStatusCode(403);
        }
        if ( ! $this->request->is('post') ) {
            return $this->response->setJSON( getMessageFail() )->setStatusCode(401);
        }else{
            if(! (new TbProdutoEntrada())->delete($this->request->getPost('id')) ){
                return $this->response->setJSON( getMessageFail() )->setStatusCode(401);
            }else{
                return $this->response->setJSON( getMessageSucess() )->setStatusCode(200);
            }
        }
    }
}
