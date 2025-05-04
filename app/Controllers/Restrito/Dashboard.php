<?php

namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\TbProduto;
use App\Models\TbProdutoEntrada;
use App\Models\TbProdutoSaida;
use App\Models\RefCategoria;

class Dashboard extends BaseController
{
    public function index()
    {
        $tbProduto = new TbProduto();
        $tbEntrada = new TbProdutoEntrada();
        $tbSaida = new TbProdutoSaida();
        $refCategoria = new RefCategoria();

        // Total de produtos
        $totalProdutos = $tbProduto->countAllResults();

        // Total de entradas e saídas nos últimos 30 dias
        $totalEntradas30d = $tbEntrada->where('data_entrada >=', date('Y-m-d', strtotime('-30 days')))->countAllResults();
        $totalSaidas30d = $tbSaida->where('data_saida >=', date('Y-m-d', strtotime('-30 days')))->countAllResults();

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
            ->groupBy('tb_produto.id_categoria')
            ->findAll();

        // Top 5 produtos mais movimentados (entradas + saídas)
        $topProdutos = $tbProduto
        ->select('tb_produto.id_produto, tb_produto.nome, ref_categoria.categoria, tb_produto.json,
                  COALESCE(SUM(e.quantidade),0) as entradas,
                  COALESCE(SUM(s.quantidade),0) as saidas,
                  (COALESCE(SUM(e.quantidade),0) + COALESCE(SUM(s.quantidade),0)) as total_movimentado')
        ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
        ->join('tb_produto_entrada e', 'e.id_produto = tb_produto.id_produto AND e.data_entrada >= "' . date('Y-m-d', strtotime('-30 days')) . '"', 'left')
        ->join('tb_produto_saida s', 's.id_produto = tb_produto.id_produto AND s.data_saida >= "' . date('Y-m-d', strtotime('-30 days')) . '"', 'left')
        ->groupBy('tb_produto.id_produto, tb_produto.nome, ref_categoria.categoria, tb_produto.json')
        ->orderBy('total_movimentado', 'DESC')
        ->limit(5)
        ->findAll();

        // Gráfico (últimos 7 dias)
        $graficoLabels = [];
        $graficoEntradas = [];
        $graficoSaidas = [];

        $ultimos30Dias = []; 
        for ($i = 29; $i >= 0; $i--) {
            $ultimos30Dias[] = date('Y-m-d', strtotime("-$i days"));
        }
        
        foreach ($ultimos30Dias as $dia) {
            $entrada = $tbEntrada->selectSum('quantidade')->where('DATE(data_entrada)', $dia)->get()->getRow();
            $saida = $tbSaida->selectSum('quantidade')->where('DATE(data_saida)', $dia)->get()->getRow();
        
            $graficoEntradas[] = (int)($entrada->quantidade ?? 0);
            $graficoSaidas[] = (int)($saida->quantidade ?? 0);
        }


        $motivosSaidas30d = $tbSaida
            ->select('ref_motivo_saida.motivo_saida, SUM(tb_produto_saida.quantidade) as total')
            ->join('ref_motivo_saida', 'ref_motivo_saida.id_motivo_saida = tb_produto_saida.id_motivo_saida')
            ->where('tb_produto_saida.data_saida >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('ref_motivo_saida.id_motivo_saida')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        return view('restrito/dashboard/index', [
            'totalProdutos'      => $totalProdutos,
            'totalEntradas30d'   => $totalEntradas30d,
            'totalSaidas30d'     => $totalSaidas30d,
            'produtosCriticos'   => $produtosCriticos,
            'categoriasQtd'      => $categoriasQtd,
            'topProdutos'        => $topProdutos,
            'graficoLabels'      => $graficoLabels,
            'graficoEntradas'    => $graficoEntradas,
            'graficoSaidas'      => $graficoSaidas,
            'motivosSaidas30d'   => $motivosSaidas30d,
            'ultimos30Dias'      => $ultimos30Dias,
        ]);
    }
}
