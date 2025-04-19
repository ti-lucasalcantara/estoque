<?php
namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;
use App\Models\TbProdutoEntrada;
class Entrada extends BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    
    public function index($id_produto=null)
    {
        return view('restrito/produto/entrada/index', $this->dados);
    }

    public function formulario($id_produto=null)
    {
        if($id_produto){
            $id_produto = base64_decode($id_produto);
            $this->dados['produto'] = (new TbProduto())
                                    ->select('tb_produto.*, ref_categoria.categoria')
                                    ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                    ->find($id_produto);
        }

        $this->dados['produtos'] = (new TbProduto())
        ->select('tb_produto.*, ref_categoria.categoria')
        ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
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
                'data_entrada' => 'required|valid_date',
                'quantidade' => 'required|is_natural_no_zero',
                'observacoes'   => 'permit_empty|max_length[500]',
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
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            $TbProdutoEntrada = new TbProdutoEntrada();
            
            $data_entrada = $this->request->getPost('data_entrada');
            $observacoes  = $this->request->getPost('observacoes');

            foreach ($this->request->getPost('produtos') as $produto) {
                $id_produto  = $produto['id_produto'];
                $quantidade  = $produto['quantidade'];

                $entrada = [
                    'id_produto'   => $id_produto,
                    'data_entrada' => $data_entrada,
                    'quantidade'   => $quantidade,
                    'observacoes'  => $observacoes,
                ];

                if($edit){
                    $entrada['id_produto_entrada'] = $id_produto_entrada;
                }

                $save = $TbProdutoEntrada->save($entrada);

                if(!$save){
                    session()->setFlashdata(getMessageFail('sweetalert'));
                    return redirect()->back()->withInput();
                }
            }

            session()->setFlashdata(getMessageSucess());
            return redirect()->route('restrito.entrada.formulario', [base64_encode($id_produto)]);

        }
    }


    public function salvarMultiplas()
    {
        dd($this->request->getPost());

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
                'data_entrada' => 'required|valid_date',
                'quantidade' => 'required|is_natural_no_zero',
                'observacoes'   => 'permit_empty|max_length[500]',
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
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            $id_produto     = $this->request->getPost('id_produto');
            $data_entrada   = $this->request->getPost('data_entrada');
            $quantidade     = $this->request->getPost('quantidade');
            $observacoes    = $this->request->getPost('observacoes');

            $entrada = [
                'id_produto' => $id_produto,
                'data_entrada' => $data_entrada,
                'quantidade' => $quantidade,
                'observacoes' => $observacoes,
            ];

            if($edit){
                $entrada['id_produto_entrada'] = $id_produto_entrada;
            }

            $TbProdutoEntrada = new TbProdutoEntrada();

            $save = $TbProdutoEntrada->save($entrada);

            if(!$save){
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }

            session()->setFlashdata(getMessageSucess());
            return redirect()->route('restrito.entrada.formulario', [base64_encode($id_produto)]);
        }
    }
}
