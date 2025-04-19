<?php
namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;
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

        return view('restrito/produto/entrada/formulario', $this->dados);
    }

    public function salvar()
    {
        if ( ! $this->request->is('post') ) {
            session()->setFlashdata( getMessageFail() );
            return redirect()->back()->withInput();   
        }else{

            $edit = false;
            $id_produto = $this->request->getPost('id_produto') ?? '';
            if(isset($id_produto) && !empty($id_produto)){
                $id_produto = base64_decode($id_produto);
                $edit = true;
            }
            $rules = [
                'nome' => 'required|max_length[255]',
                'codigo' => 'required|max_length[100]|is_unique[tb_produto.codigo,id_produto,'.$id_produto.']',
                'id_categoria' => 'required',
                'descricao'   => 'permit_empty|max_length[500]',
            ];

            $messages   = [
                'nome' => [
                    'required' => 'Campo obrigatório.',
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido.',
                ],
                'id_categoria' => [
                    'required' => 'Campo obrigatório.',
                ],
                'codigo' => [
                    'required' => 'Campo obrigatório.',
                    'is_unique' => 'Código já existente.',
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido.',
                ],
                'descricao' => [
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido, máximo 500 caracteres.',
                ],
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return redirect()->back()->withInput();   
            }

            $nome           = $this->request->getPost('nome');
            $codigo         = $this->request->getPost('codigo');
            $id_categoria   = $this->request->getPost('id_categoria');
            $descricao      = $this->request->getPost('descricao');

            $produto = [
                'nome' => $nome,
                'codigo' => $codigo,
                'id_categoria' => $id_categoria,
                'descricao' => $descricao,
            ];

            if($edit){
                $produto['id_produto'] = $id_produto;
            }

            $TbProduto = new TbProduto();

            $save = $TbProduto->save($produto);

            if(!$save){
                session()->setFlashdata(getMessageFail('sweetalert'));
                return redirect()->back()->withInput();
            }

            session()->setFlashdata(getMessageSucess());
            
            if($edit){
                return redirect()->route('restrito.produto.editar', [base64_encode($id_produto)]);
            }
            return redirect()->route('restrito.produto.index');
        }
    }
}
