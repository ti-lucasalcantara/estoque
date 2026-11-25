<?php

namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;
use App\Models\TbProdutoEntrada;
use App\Models\TbProdutoSaida;

class Dashboard extends BaseController
{
    public function index()
    {
        $tbProduto = new TbProduto();
        $tbEntrada = new TbProdutoEntrada();
        $tbSaida = new TbProdutoSaida();
        $inicio = date('Y-m-d', strtotime('-29 days'));
        $fim = date('Y-m-d') . ' 23:59:59';

        // Total de produtos
        $totalProdutos = $tbProduto->countAllResults();

        // Total de entradas e saídas nos últimos 30 dias
        $totalEntradas30d = $tbEntrada->where('data_entrada >=', $inicio)->where('data_entrada <=', $fim)->countAllResults();
        $totalSaidas30d = $tbSaida->where('data_saida >=', $inicio)->where('data_saida <=', $fim)->countAllResults();

        // Produtos abaixo do estoque mínimo
        $produtosCriticos = $tbProduto
            ->select('tb_produto.*, fn_saldo_estoque(tb_produto.id_produto) AS saldoEstoque, ref_categoria.categoria')
            ->join('ref_categoria', 'ref_categoria.id_categoria=tb_produto.id_categoria')
            ->having('saldoEstoque < estoque_minimo')
            ->orderBy('tb_produto.nome', 'ASC')
            ->findAll();

        // Quantitativo por categoria
        $categoriasQtd = $tbProduto
            ->select('ref_categoria.categoria, COUNT(tb_produto.id_produto) as qtd')
            ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
            ->groupBy(['tb_produto.id_categoria', 'ref_categoria.categoria'])
            ->findAll();

        $entradas = $tbEntrada->builder()->where('deleted_at', null)
            ->where('data_entrada >=', $inicio)->where('data_entrada <=', $fim);
        $saidas = $tbSaida->builder()->where('deleted_at', null)
            ->where('data_saida >=', $inicio)->where('data_saida <=', $fim);
        $entradasPorProduto = (clone $entradas)->select('id_produto')->selectSum('quantidade')
            ->groupBy('id_produto')->getCompiledSelect();
        $saidasPorProduto = (clone $saidas)->select('id_produto')->selectSum('quantidade')
            ->groupBy('id_produto')->getCompiledSelect();

        $topProdutos = $tbProduto
        ->select('tb_produto.id_produto, tb_produto.nome, ref_categoria.categoria, tb_produto.id_cor,
                  COALESCE(e.quantidade, 0) AS entradas, COALESCE(s.quantidade, 0) AS saidas,
                  (COALESCE(e.quantidade, 0) + COALESCE(s.quantidade, 0)) AS total_movimentado', false)
        ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
        ->join('(' . $entradasPorProduto . ') e', 'e.id_produto = tb_produto.id_produto', 'left', false)
        ->join('(' . $saidasPorProduto . ') s', 's.id_produto = tb_produto.id_produto', 'left', false)
        ->having('total_movimentado >', 0)
        ->orderBy('total_movimentado', 'DESC')
        ->limit(5)
        ->findAll();

        $topProdutosSaidas = $tbProduto
        ->select('tb_produto.id_produto, tb_produto.nome, ref_categoria.categoria, tb_produto.id_cor, s.quantidade AS saidas')
        ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
        ->join('(' . $saidasPorProduto . ') s', 's.id_produto = tb_produto.id_produto', 'inner', false)
        ->orderBy('saidas', 'DESC')
        ->limit(5)
        ->findAll();

        $entradasPorDia = $entradas->select('DATE(data_entrada) AS dia', false)->selectSum('quantidade')
            ->groupBy('DATE(data_entrada)', false)->get()->getResultArray();
        $saidasPorDia = $saidas->select('DATE(data_saida) AS dia', false)->selectSum('quantidade')
            ->groupBy('DATE(data_saida)', false)->get()->getResultArray();
        $entradasPorDia = array_column($entradasPorDia, 'quantidade', 'dia');
        $saidasPorDia = array_column($saidasPorDia, 'quantidade', 'dia');
        $graficoEntradas = [];
        $graficoSaidas = [];

        $ultimos30Dias = []; 
        for ($i = 29; $i >= 0; $i--) {
            $ultimos30Dias[] = date('Y-m-d', strtotime("-$i days"));
        }
        
        foreach ($ultimos30Dias as $dia) {
            $graficoEntradas[] = (int) ($entradasPorDia[$dia] ?? 0);
            $graficoSaidas[] = (int) ($saidasPorDia[$dia] ?? 0);
        }


        $motivosSaidas30d = $tbSaida
            ->select('ref_motivo_saida.motivo_saida, SUM(tb_produto_saida.quantidade) as total')
            ->join('ref_motivo_saida', 'ref_motivo_saida.id_motivo_saida = tb_produto_saida.id_motivo_saida')
            ->where('tb_produto_saida.data_saida >=', $inicio)
            ->where('tb_produto_saida.data_saida <=', $fim)
            ->groupBy(['ref_motivo_saida.id_motivo_saida', 'ref_motivo_saida.motivo_saida'])
            ->orderBy('total', 'DESC')
            ->findAll();

        return view('restrito/dashboard/index', [
            'totalProdutos'      => $totalProdutos,
            'totalEntradas30d'   => $totalEntradas30d,
            'totalSaidas30d'     => $totalSaidas30d,
            'produtosCriticos'   => $produtosCriticos,
            'categoriasQtd'      => $categoriasQtd,
            'topProdutos'        => $topProdutos,
            'topProdutosSaidas'  => $topProdutosSaidas,
            'graficoEntradas'    => $graficoEntradas,
            'graficoSaidas'      => $graficoSaidas,
            'motivosSaidas30d'   => $motivosSaidas30d,
            'ultimos30Dias'      => $ultimos30Dias,
        ]);
    }
}
