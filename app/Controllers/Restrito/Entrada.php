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

        $data_inicio = $this->request->getGet('data_inicio') ?? date('Y-m-01');
        $data_fim = $this->request->getGet('data_fim') ?? date('Y-m-d');

        $TbProdutoEntrada = new TbProdutoEntrada();

        $this->dados['tb_produto_entrada'] = $TbProdutoEntrada
                                                ->select('tb_produto_entrada.*, tb_produto.nome AS "produto", ref_categoria.categoria')
                                                ->join('tb_produto', 'tb_produto.id_produto=tb_produto_entrada.id_produto')
                                                ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                                ->where('tb_produto_entrada.data_entrada BETWEEN "' . $data_inicio . '" AND "' . $data_fim . '"')
                                                ->orderBy('data_entrada', 'DESC')
                                                ->findAll();

        $this->dados['data_inicio'] = $data_inicio;
        $this->dados['data_fim'] = $data_fim;

        return view('restrito/produto/entrada/index', $this->dados);
    }

    public function formulario($id_produto=null, $id_produto_entrada=null)
    {
        if($id_produto){
            $id_produto = base64_decode($id_produto);
            $this->dados['produto'] = (new TbProduto())
                                    ->select('tb_produto.*, ref_categoria.categoria')
                                    ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                    ->find($id_produto);
        }

        if($id_produto_entrada){
            $id_produto_entrada = base64_decode($id_produto_entrada);
            $this->dados['produto_entrada'] = (new TbProdutoEntrada())->find($id_produto_entrada);
        }

        $this->dados['ref_local']           = (new RefLocal())->orderBy('local', 'ASC')->findAll();
        $this->dados['ref_motivo_entrada']  = (new RefMotivoEntrada())->orderBy('motivo_entrada', 'ASC')->findAll();
        $this->dados['entradas']  = (new TbProdutoEntrada())
                                    ->select('tb_produto_entrada.*, ref_local.local as "local", tb_usuario.nome AS "usuarioCriacao", ref_motivo_entrada.motivo_entrada ')
                                    ->join('tb_usuario', 'tb_usuario.id_usuario=tb_produto_entrada.user_created')
                                    ->join('ref_motivo_entrada', 'ref_motivo_entrada.id_motivo_entrada=tb_produto_entrada.id_motivo_entrada', 'left')
                                    ->join('ref_local', 'ref_local.id_local=tb_produto_entrada.id_local')
                                    ->where('id_produto', $id_produto)
                                    ->orderBy('data_entrada','DESC')
                                    ->findAll();

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
        ->select('tb_produto.*, ref_categoria.categoria')
        ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
        ->findAll();

        return view('restrito/produto/entrada/formulario_multiplas', $this->dados);
    }

    public function salvarMultiplas()
    {
        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   
        }else{

            $rules = [
                'data_entrada' => 'required|valid_date',
                'observacoes'   => 'permit_empty|max_length[500]',
            ];

            $messages   = [
                'data_entrada' => [
                    'required' => 'Campo obrigatório.',
                    'valid_date' => 'Data inválida, verifique o campo informado.',
                ],
               'observacoes' => [
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido, máximo 500 caracteres.',
                ],
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            $entradas = array();

            $produtos = $this->request->getPost('produtos');

            $data_entrada   = $this->request->getPost('data_entrada');
            $observacoes    = $this->request->getPost('observacoes');
          
            foreach ($produtos as $produto) {
                $id_produto = $produto['id_produto'];
                $quantidade = $produto['quantidade'];

                array_push($entradas, [
                    'id_produto' => $id_produto,
                    'data_entrada' => $data_entrada,
                    'quantidade' => $quantidade,
                    'observacoes' => $observacoes,
                ]);

            }

            $TbProdutoEntrada = new TbProdutoEntrada();
            $save = $TbProdutoEntrada->insertBatch($entradas);

            if(!$save){
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }

            session()->setFlashdata(getMessageSucess());
            return redirect()->route('restrito.entrada.index');
        }
    }
        
    public function excluir() 
    {
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
