<?php
namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;
use App\Models\TbProdutoSaida;
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

        $data_inicio = $this->request->getGet('data_inicio') ?? date('Y-m-01');
        $data_fim = $this->request->getGet('data_fim') ?? date('Y-m-d');

        $TbProdutoSaida = new TbProdutoSaida();

        $this->dados['tb_produto_saida'] = $TbProdutoSaida
                                                ->select('tb_produto_saida.*, tb_produto.nome AS "produto", ref_categoria.categoria, ref_motivo_saida.motivo_saida')
                                                ->join('tb_produto', 'tb_produto.id_produto=tb_produto_saida.id_produto')
                                                ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                                ->join('ref_motivo_saida', 'ref_motivo_saida.id_motivo_saida=tb_produto_saida.id_motivo_saida')
                                                ->where('tb_produto_saida.data_saida BETWEEN "' . $data_inicio . '" AND "' . $data_fim . '"')
                                                ->orderBy('data_saida', 'DESC')
                                                ->findAll();

        foreach ($this->dados['tb_produto_saida'] as $key => $value) {
            $this->dados['tb_produto_saida'][$key]['TbProduto'] = (new TbProduto())->find($value['id_produto']);
        }

        $this->dados['data_inicio'] = $data_inicio;
        $this->dados['data_fim'] = $data_fim;

        return view('restrito/produto/saida/index', $this->dados);
    }

    public function formulario($id_produto=null, $id_produto_saida=null)
    {
        if($id_produto){
            $id_produto = base64_decode($id_produto);
            $this->dados['produto'] = (new TbProduto())
                                    ->select('tb_produto.*, ref_categoria.categoria')
                                    ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                    ->find($id_produto);

                                    


            $this->dados['produto']['cor_nome'] = getNomeCorProduto($this->dados['produto'] );
            $this->dados['produto']['cor_hexa'] = getHexaCorProduto($this->dados['produto'] );
        }

        if($id_produto_saida){
            $id_produto_saida = base64_decode($id_produto_saida);
            $this->dados['produto_saida'] = (new TbProdutoSaida())->find($id_produto_saida);
        }

        $this->dados['ref_local']           = (new RefLocal())->orderBy('local', 'ASC')->findAll();
        $this->dados['ref_motivo_saida']  = (new RefMotivoSaida())->orderBy('motivo_saida', 'ASC')->findAll();
        $this->dados['saidas']  = (new TbProdutoSaida())
                                    ->select('tb_produto_saida.*, ref_local.local as "local", tb_usuario.nome AS "usuarioCriacao", ref_motivo_saida.motivo_saida ')
                                    ->join('tb_usuario', 'tb_usuario.id_usuario=tb_produto_saida.user_created','left')
                                    ->join('ref_motivo_saida', 'ref_motivo_saida.id_motivo_saida=tb_produto_saida.id_motivo_saida', 'left')
                                    ->join('ref_local', 'ref_local.id_local=tb_produto_saida.id_local', 'left')
                                    ->where('id_produto', $id_produto)
                                    ->orderBy('data_saida','DESC')
                                    ->findAll();

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

            $TbProdutoSaida = new TbProdutoSaida();
            
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

            if($edit){
                $saida['id_produto_saida'] = $id_produto_saida;
            }

            $save = $TbProdutoSaida->save($saida);

            if(!$save){
                session()->setFlashdata(getMessageFail('sweetalert'));
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
                                    ->select('tb_produto.*, ref_categoria.categoria')
                                    ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
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

            $saidas = array();

            $produtos = $this->request->getPost('produtos');

            $data_saida       = $this->request->getPost('data_saida');
            $observacoes        = $this->request->getPost('observacoes');
            $id_local           = $this->request->getPost('id_local');
            $id_motivo_saida  = $this->request->getPost('id_motivo_saida');

            foreach ($produtos as $produto) {
                $id_produto = $produto['id_produto'];
                $quantidade = $produto['quantidade'];

                if(is_array($id_produto)){
                    foreach ($id_produto as $value) {
                        array_push($saidas, [
                            'id_produto' => $value,
                            'data_saida' => $data_saida,
                            'quantidade' => $quantidade,
                            'observacoes' => $observacoes,
                            'id_local' => $id_local,
                            'id_motivo_saida' => $id_motivo_saida,
                        ]);
                    }
                }else{
                    array_push($saidas, [
                        'id_produto' => $id_produto,
                        'data_saida' => $data_saida,
                        'quantidade' => $quantidade,
                        'observacoes' => $observacoes,
                        'id_local' => $id_local,
                        'id_motivo_saida' => $id_motivo_saida,
                    ]);
                }
            }

            $TbProdutoSaida = new TbProdutoSaida();
            $save = $TbProdutoSaida->insertBatch($saidas);

            if(!$save){
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }

            session()->setFlashdata(getMessageSucess());
            return redirect()->route('restrito.saida.index');
        }
    }
        
    public function excluir() 
    {
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
