<?php

namespace App\Controllers\Restrito;
use App\Models\TbProduto;

class Relatorio extends \App\Controllers\BaseController
{
    private $dados;

    public function __construct(){
        $this->dados = array();
    }
    

    public function index()
    {
        $this->dados['relatorios'] = [
            ['titulo' => 'Saldo atual de estoque', 'rota' => 'restrito.relatorio.saldoEstoque'],
            ['titulo' => 'Movimentação de produtos', 'rota' => 'restrito.relatorio.movimentacaoProduto'],

            ['titulo' => 'Produtos sem movimentação', 'rota' => 'relatorios/sem_movimentacao'],
            ['titulo' => 'Produtos em estoque minimo', 'rota' => 'relatorios/sem_movimentacao'],
            
            ['titulo' => 'Saídas por usuário/setor', 'rota' => 'relatorios/saida_usuario'],
            ['titulo' => 'Consumo por produto', 'rota' => 'relatorios/consumo_produto'],
            
        ];

        return view('restrito/relatorio/index', $this->dados);
    }

    public function saldoEstoque()
    {
        $parametros = [];
        $parametros['ref_categoria'] = $this->request->getGet('ref_categoria') ?? '';
        $parametros['tb_produtos'] = $this->request->getGet('tb_produtos') ?? '';
        

        if(isset($parametros) && !empty($parametros['ref_categoria'])){
            $this->dados['produtos'] = (new TbProduto())
                                        ->whereIn('id_produto', $parametros['tb_produtos'])
                                        ->select('tb_produto.*, ref_categoria.categoria, fn_saldo_estoque(tb_produto.id_produto) AS "saldoEstoque" ')
                                        ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                        ->findAll();
        }

        return view('restrito/relatorio/saldoEstoque', $this->dados);
    }


    public function movimentacaoProduto()
    {
        return view('restrito/relatorio/movimentacaoProduto', $this->dados);
    }

    

}
