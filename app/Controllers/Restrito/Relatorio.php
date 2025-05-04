<?php

namespace App\Controllers\Restrito;
use App\Models\TbProduto;
use App\Models\TbProdutoEntrada;
use App\Models\TbProdutoSaida;

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
        

        if(isset($parametros) && !empty($parametros['tb_produtos'])){
            $this->dados['produtos'] = (new TbProduto())
                                        ->whereIn('id_produto', $parametros['tb_produtos'])
                                        ->select('tb_produto.*, ref_categoria.categoria, fn_saldo_estoque(tb_produto.id_produto) AS "saldoEstoque" ')
                                        ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                        ->orderBy('tb_produto.nome', 'ASC')
                                        ->findAll();
        }

        return view('restrito/relatorio/saldoEstoque', $this->dados);
    }


    public function movimentacaoProduto()
    {

        $parametros = [];
        $parametros['ref_categoria']    = $this->request->getGet('ref_categoria') ?? '';
        $parametros['tb_produtos']      = $this->request->getGet('tb_produtos') ?? '';
        $parametros['tipo_periodo']     = $this->request->getGet('tipo_periodo') ?? '';
        $parametros['data_inicio']      = $this->request->getGet('data_inicio') ?? '';
        $parametros['data_fim']         = $this->request->getGet('data_fim') ?? '';
        
        if(isset($parametros) && !empty($parametros['tb_produtos'])){

            $this->dados['produtos'] = (new TbProduto())
                                        ->whereIn('id_produto', $parametros['tb_produtos'])
                                        ->select('tb_produto.*, ref_categoria.categoria, fn_saldo_estoque(tb_produto.id_produto) AS "saldoEstoque" ')
                                        ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
                                        ->orderBy('tb_produto.nome', 'ASC')
                                        ->findAll();



            $builder = (new TbProdutoEntrada())
                ->whereIn('tb_produto_entrada.id_produto', $parametros['tb_produtos'])
                ->select('tb_produto_entrada.*, ref_motivo_entrada.motivo_entrada, tb_usuario.nome AS usuarioCriacao ')
                ->join('ref_motivo_entrada', 'ref_motivo_entrada.id_motivo_entrada=tb_produto_entrada.id_motivo_entrada')
                ->join('tb_usuario', 'tb_usuario.id_usuario=tb_produto_entrada.user_created', 'left');
                
            if ($parametros['tipo_periodo'] == 'especifico' && !empty($parametros['data_inicio']) && !empty($parametros['data_fim'])) {
                $builder->where('tb_produto_entrada.data_entrada >=', $parametros['data_inicio'])
                        ->where('tb_produto_entrada.data_entrada <=', $parametros['data_fim']);
            }

            $this->dados['entradas'] = $builder->orderBy('tb_produto_entrada.data_entrada', 'DESC')->findAll();
                                        

            $builder = (new TbProdutoSaida())
                ->whereIn('tb_produto_saida.id_produto', $parametros['tb_produtos'])
                ->select('tb_produto_saida.*, ref_motivo_saida.motivo_saida, tb_usuario.nome AS usuarioCriacao ')
                ->join('ref_motivo_saida', 'ref_motivo_saida.id_motivo_saida=tb_produto_saida.id_motivo_saida')
                ->join('tb_usuario', 'tb_usuario.id_usuario=tb_produto_saida.user_created', 'left');

            if ($parametros['tipo_periodo'] == 'especifico' && !empty($parametros['data_inicio']) && !empty($parametros['data_fim'])) {
                $builder->where('tb_produto_saida.data_saida >=', $parametros['data_inicio'])
                        ->where('tb_produto_saida.data_saida <=', $parametros['data_fim']);
            }

            $this->dados['saidas'] = $builder->orderBy('tb_produto_saida.data_saida', 'DESC')->findAll();
                                        
            
            $movimentos = [];

            #d($this->dados['entradas']  );

            foreach ($this->dados['entradas'] as $e) {
                $movimentos[] = [
                    'data'        => $e['data_entrada'],
                    'tipo'        => 'entrada',
                    'motivo'      => $e['motivo_entrada'],
                    'quantidade'  => $e['quantidade'],
                    'usuario'     => $e['usuarioCriacao'],
                    'id_produto'  => $e['id_produto'],
                    'observacoes' => $e['observacoes'],
                ];
            }

            #d($movimentos );

            foreach ($this->dados['saidas'] as $s) {
                $movimentos[] = [
                    'data'        => $s['data_saida'],
                    'tipo'        => 'saida',
                    'motivo'      => $s['motivo_saida'],
                    'quantidade'  => $s['quantidade'],
                    'usuario'     => $s['usuarioCriacao'],
                    'id_produto'  => $s['id_produto'],
                    'observacoes' => $s['observacoes'],
                ];
            }
            #d($movimentos );

            #dd('fim');

            // Ordena por data crescente
            usort($movimentos, fn($a, $b) => strtotime($a['data']) <=> strtotime($b['data']));

            $this->dados['movimentos'] = $movimentos;
        }


        return view('restrito/relatorio/movimentacaoProduto', $this->dados);
    }

    

}
