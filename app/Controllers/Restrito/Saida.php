<?php
namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;
use App\Models\TbProdutoSaida;
use App\Libraries\RegistroSaida;
use App\Models\RefLocal;
use App\Models\RefMotivoSaida;

class Saida extends BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    
    public function index()
    {
        return view('restrito/produto/saida/index', \App\Libraries\HistoricoMovimentacoes::consultar(
            'saida',
            $this->request->getGet('data_inicio'),
            $this->request->getGet('data_fim')
        ));
    }

    public function formulario($id_produto=null, $id_produto_saida=null)
    {
        if ($id_produto_saida !== null && !(new \App\Libraries\PoliticaCorrecoes())->permiteCorrigir()) {
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

        if($id_produto_saida){
            $id_produto_saida = base64_decode($id_produto_saida);
            $this->dados['produto_saida'] = (new TbProdutoSaida())->find($id_produto_saida);
            $registro = $this->dados['produto_saida'];
            if (!$registro || (int) $registro['id_produto'] !== (int) $id_produto) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $this->dados['ref_local']           = (new RefLocal())->orderBy('local', 'ASC')->findAll();
        $this->dados['ref_motivo_saida']  = (new RefMotivoSaida())->orderBy('motivo_saida', 'ASC')->findAll();
        $this->dados['saidas'] = [];
        if (\App\Libraries\PermissoesEstoque::tem('saida.ver')) {
            $this->dados['saidas']  = (new TbProdutoSaida())
                                    ->select('tb_produto_saida.*, ref_local.local as "local", tb_usuario.nome AS "usuarioCriacao", ref_motivo_saida.motivo_saida ')
                                    ->join('tb_usuario', 'tb_usuario.id_usuario=tb_produto_saida.user_created','left')
                                    ->join('ref_motivo_saida', 'ref_motivo_saida.id_motivo_saida=tb_produto_saida.id_motivo_saida', 'left')
                                    ->join('ref_local', 'ref_local.id_local=tb_produto_saida.id_local', 'left')
                                    ->where('id_produto', $id_produto)
                                    ->orderBy('data_saida','DESC')
                                    ->findAll();
        }

        return view('restrito/produto/saida/formulario', $this->dados);
    }

    public function salvar()
    {
        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   
        }else{

            $edit = false;
            $id_produto_saida = $this->request->getPost('id_produto_saida') ?? '';
            if(isset($id_produto_saida) && !empty($id_produto_saida)){
                $id_produto_saida = base64_decode($id_produto_saida);
                $edit = true;
                if (!(new \App\Libraries\PoliticaCorrecoes())->permiteCorrigir()) {
                    return $this->response->setStatusCode(403)->setBody('Somente administradores podem corrigir movimentações.');
                }
            }

            $rules = [
                'data_saida'  => 'required|valid_date',
                'quantidade'    => 'required|is_natural_no_zero',
                'observacoes'   => 'permit_empty|max_length[500]',
                'id_local'      => 'required',
                'id_motivo_saida'  => 'required',
            ];

            $messages   = [
                'data_saida' => [
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
                'id_motivo_saida' => [
                    'required' => 'Campo obrigatório.',
                ],
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }
            
            $id_produto   = $this->request->getPost('id_produto');
            $quantidade   = $this->request->getPost('quantidade');
            $data_saida = $this->request->getPost('data_saida');
            $observacoes  = $this->request->getPost('observacoes');
            $id_local     = $this->request->getPost('id_local');
            $id_motivo_saida = $this->request->getPost('id_motivo_saida');

            $saida = [
                'id_produto'   => $id_produto,
                'data_saida' => $data_saida,
                'quantidade'   => $quantidade,
                'observacoes'  => $observacoes,
                'id_local'     => $id_local,
                'id_motivo_saida' => $id_motivo_saida,
            ];

            if ($edit && !ctype_digit((string) $id_produto_saida)) {
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }

            if (!$this->registrarSaidas([$saida], $edit ? (int) $id_produto_saida : null)) {
                return redirect()->back()->withInput();
            }

            session()->setFlashdata(getMessageSucess());

            if($edit){
                return redirect()->route('restrito.saida.editar', [base64_encode($id_produto), base64_encode($id_produto_saida)]);
            }
            return redirect()->route('restrito.saida.formulario', [base64_encode($id_produto)]);
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

        $this->dados['ref_motivo_saida']  = (new RefMotivoSaida())->orderBy('motivo_saida', 'ASC')->findAll();
        $this->dados['ref_local']           = (new RefLocal())->orderBy('local', 'ASC')->findAll();

        return view('restrito/produto/saida/formulario_multiplas', $this->dados);
    }

    public function salvarMultiplas()
    {
        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   
        }else{

            $rules = [
                'data_saida'  => 'required|valid_date',
                'observacoes'   => 'permit_empty|max_length[500]',
                'id_local'      => 'required',
                'id_motivo_saida'  => 'required',
            ];

            $messages   = [
                'data_saida' => [
                    'required' => 'Campo obrigatório.',
                    'valid_date' => 'Data inválida, verifique o campo informado.',
                ],
               'observacoes' => [
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido, máximo 500 caracteres.',
                ],
                'id_local' => [
                    'required' => 'Campo obrigatório.',
                ],
                'id_motivo_saida' => [
                    'required' => 'Campo obrigatório.',
                ],
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            $saidas = [];
            $produtos = $this->request->getPost('produtos');
            $dadosComuns = [
                'data_saida' => $this->request->getPost('data_saida'),
                'observacoes' => $this->request->getPost('observacoes'),
                'id_local' => $this->request->getPost('id_local'),
                'id_motivo_saida' => $this->request->getPost('id_motivo_saida'),
            ];

            foreach ((array) $produtos as $produto) {
                $ids = (array) ($produto['id_produto'] ?? []);
                foreach ($ids ?: [null] as $idProduto) {
                    $saidas[] = $dadosComuns + [
                        'id_produto' => $idProduto,
                        'quantidade' => $produto['quantidade'] ?? null,
                    ];
                }
            }

            if (!$this->registrarSaidas($saidas)) {
                return redirect()->back()->withInput();
            }
            session()->setFlashdata(getMessageSucess());
            return redirect()->route(\App\Libraries\PermissoesEstoque::tem('saida.ver')
                ? 'restrito.saida.index' : 'restrito.saida.formularioMultiplas');
        }
    }
        
    private function registrarSaidas(array $saidas, ?int $idSaida = null): bool
    {
        try {
            (new RegistroSaida())->salvar($saidas, $idSaida);
            return true;
        } catch (\App\Libraries\SaldoInsuficiente $erro) {
            session()->setFlashdata(getMessageFail('sweetalert', [
                'title' => 'Saldo insuficiente',
                'text' => $erro->getMessage(),
            ]) + [
                'confirmButtonText' => 'Ajustar quantidade',
                'saldo_insuficiente' => [
                    'produto' => $erro->produto,
                    'disponivel' => $erro->disponivel,
                    'solicitado' => $erro->solicitado,
                ],
            ]);
        } catch (\DomainException $erro) {
            session()->setFlashdata(getMessageFail('sweetalert', [
                'title' => 'Falha ao registrar saída',
                'text' => esc($erro->getMessage()),
            ]));
        } catch (\Throwable $erro) {
            log_message('error', 'Falha ao gravar saída: {exception}', ['exception' => $erro]);
            session()->setFlashdata(getMessageFail('sweetalert'));
        }

        return false;
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
            if(! (new TbProdutoSaida())->delete($this->request->getPost('id')) ){
                return $this->response->setJSON( getMessageFail() )->setStatusCode(401);
            }else{
                return $this->response->setJSON( getMessageSucess() )->setStatusCode(200);
            }
        }
    }
}
