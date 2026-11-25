<?php

namespace App\Controllers\Restrito;

use App\Controllers\BaseController;
use App\Models\RefCategoria;
use App\Models\TbProduto;
use App\Models\TbProdutoEntrada;
use App\Models\TbProdutoSaida;

class Relatorio extends BaseController
{
    public function index()
    {
        return view('restrito/relatorio/index', ['relatorios' => [
            ['titulo' => 'Saldo de estoque', 'descricao' => 'Consulte o saldo disponível por produto e cor.', 'icone' => 'cubes', 'rota' => 'restrito.relatorio.saldoEstoque'],
            ['titulo' => 'Produtos abaixo do mínimo', 'descricao' => 'Veja quantas unidades faltam para atingir o estoque mínimo.', 'icone' => 'exclamation-circle', 'rota' => 'restrito.relatorio.estoqueMinimo'],
            ['titulo' => 'Movimentações por produto', 'descricao' => 'Acompanhe entradas, saídas e saldo ao longo do período.', 'icone' => 'exchange', 'rota' => 'restrito.relatorio.movimentacaoProduto'],
            ['titulo' => 'Produtos com poucas saídas', 'descricao' => 'Identifique os produtos com menor giro no período.', 'icone' => 'line-chart', 'rota' => 'restrito.relatorio.poucaMovimentacao'],
        ]]);
    }

    public function saldoEstoque()
    {
        return $this->posicaoEstoque(false);
    }

    public function estoqueMinimo()
    {
        return $this->posicaoEstoque(true);
    }

    private function posicaoEstoque(bool $somenteCriticos)
    {
        $dados = $this->filtros();
        $dados['titulo'] = $somenteCriticos ? 'Produtos abaixo do mínimo' : 'Saldo de estoque';
        $dados['rotaConsulta'] = $somenteCriticos ? 'restrito.relatorio.estoqueMinimo' : 'restrito.relatorio.saldoEstoque';
        $dados['descricao'] = $somenteCriticos
            ? 'Veja o saldo atual e quantas unidades faltam para atingir o mínimo de cada produto.'
            : 'Confira o saldo atual, o estoque mínimo e a situação de cada variação de cor.';
        $consulta = $this->produtosFiltrados($dados['filtros'])
            ->select('tb_produto.estoque_minimo, fn_saldo_estoque(tb_produto.id_produto) AS saldoEstoque');
        if ($somenteCriticos) {
            $consulta->having('saldoEstoque < tb_produto.estoque_minimo');
        }
        $dados['produtos'] = $consulta->findAll();

        return view('restrito/relatorio/' . ($somenteCriticos ? 'estoqueMinimo' : 'saldoEstoque'), $dados);
    }

    public function movimentacaoProduto()
    {
        $dados = $this->filtros(true);
        $dados['titulo'] = 'Movimentações por produto';
        $dados['rotaConsulta'] = 'restrito.relatorio.movimentacaoProduto';
        $dados['descricao'] = 'Acompanhe entradas, saídas e o saldo de cada produto no período selecionado.';
        $dados['produtos'] = $dados['periodoValido'] ? $this->produtosFiltrados($dados['filtros'])->findAll() : [];
        $dados['movimentos'] = [];
        $dados['saldosIniciais'] = [];
        $ids = array_column($dados['produtos'], 'id_produto');
        $inicio = $dados['inicio'];
        $fim = $dados['fim'];

        if ($ids) {
            foreach (['entrada' => new TbProdutoEntrada(), 'saida' => new TbProdutoSaida()] as $tipo => $model) {
                $tabela = 'tb_produto_' . $tipo;
                $campoData = 'data_' . $tipo;
                $motivo = 'motivo_' . $tipo;
                $refMotivo = 'ref_' . $motivo;
                if ($inicio !== null) {
                    $anteriores = $model->select('id_produto')->selectSum('quantidade', 'total')
                        ->whereIn('id_produto', $ids)->where($campoData . ' <', $inicio)->groupBy('id_produto')->findAll();
                    foreach ($anteriores as $linha) {
                        $id = $linha['id_produto'];
                        $dados['saldosIniciais'][$id] = ($dados['saldosIniciais'][$id] ?? 0)
                            + ($tipo === 'entrada' ? 1 : -1) * (int) $linha['total'];
                    }
                }

                $model->select("$tabela.id_produto, $tabela.id_produto_$tipo AS id, $campoData AS data,
                    quantidade, observacoes, $refMotivo.$motivo AS motivo, tb_usuario.nome AS usuario")
                    ->join($refMotivo, "$refMotivo.id_$motivo = $tabela.id_$motivo", 'left')
                    ->join('tb_usuario', "tb_usuario.id_usuario = $tabela.user_created", 'left')
                    ->whereIn("$tabela.id_produto", $ids);
                if ($inicio !== null) {
                    $model->where($campoData . ' >=', $inicio)->where($campoData . ' <=', $fim . ' 23:59:59');
                }
                foreach ($model->findAll() as $movimento) {
                    $dados['movimentos'][$movimento['id_produto']][] = $movimento + ['tipo' => $tipo];
                }
            }

            foreach ($dados['movimentos'] as &$linhas) {
                usort($linhas, static fn ($a, $b) => [$a['data'], $a['tipo'], (int) $a['id']] <=> [$b['data'], $b['tipo'], (int) $b['id']]);
            }
            unset($linhas);
        }

        return view('restrito/relatorio/movimentacaoProduto', $dados);
    }

    public function poucaMovimentacao()
    {
        $dados = $this->filtros(true);
        $dados['titulo'] = 'Produtos com poucas saídas';
        $dados['rotaConsulta'] = 'restrito.relatorio.poucaMovimentacao';
        $dados['descricao'] = 'Consulte até 30 produtos, começando pelos que tiveram menos unidades retiradas no período.';
        $dados['produtos'] = [];
        if ($dados['periodoValido']) {
            $saidas = db_connect()->table('tb_produto_saida')->select('id_produto')->selectSum('quantidade', 'total')
                ->where('deleted_at', null)->groupBy('id_produto');
            if ($dados['inicio'] !== null) {
                $saidas->where('data_saida >=', $dados['inicio'])->where('data_saida <=', $dados['fim'] . ' 23:59:59');
            }
            $dados['produtos'] = $this->produtosFiltrados($dados['filtros'], false)
                ->select('COALESCE(saidas.total, 0) AS total_saidas', false)
                ->join('(' . $saidas->getCompiledSelect() . ') saidas', 'saidas.id_produto = tb_produto.id_produto', 'left', false)
                ->orderBy('total_saidas', 'ASC', false)
                ->orderBy('tb_produto.nome')->orderBy('tb_produto.id_produto')
                ->findAll(30);
        }
        return view('restrito/relatorio/poucaMovimentacao', $dados);
    }

    private function produtosFiltrados(array $filtros, bool $ordenar = true): TbProduto
    {
        $produtos = $this->consultaProdutos($ordenar);
        foreach (['id_categoria' => 'categorias', 'id_produto' => 'produtos'] as $coluna => $filtro) {
            if ($filtros[$filtro]) {
                $produtos->whereIn('tb_produto.' . $coluna, $filtros[$filtro]);
            }
        }

        return $produtos;
    }

    private function consultaProdutos(bool $ordenar = true): TbProduto
    {
        $produtos = (new TbProduto())
            ->select('tb_produto.id_produto, tb_produto.nome, tb_produto.codigo, tb_produto.id_categoria, ref_categoria.categoria,
                      ref_cor.nome AS cor_nome, ref_cor.hexadecimal AS cor_hex')
            ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
            ->join('ref_cor', 'ref_cor.id_cor = tb_produto.id_cor AND ref_cor.deleted_at IS NULL', 'left');
        if ($ordenar) {
            $produtos->orderBy('tb_produto.nome')->orderBy('tb_produto.id_produto');
        }
        return $produtos;
    }

    private function filtros(bool $comPeriodo = false): array
    {
        $hoje = date('Y-m-d');
        $periodo = $this->request->getGet('tipo_periodo');
        $todo = $comPeriodo && in_array($periodo, ['todo', 'tudo'], true);
        $inicio = $todo || !$comPeriodo ? null : ($this->request->getGet('data_inicio') ?? date('Y-m-01'));
        $fim = $todo || !$comPeriodo ? null : ($this->request->getGet('data_fim') ?? $hoje);
        $periodoValido = $inicio === null || ($this->dataValida($inicio) && $this->dataValida($fim) && $inicio <= $fim && $fim <= $hoje);
        $ids = static fn ($valor) => array_values(array_filter(
            array_map(static fn ($id) => filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]), (array) $valor)
        ));

        return [
            'filtros' => [
                'categorias' => $ids($this->request->getGet('ref_categoria')),
                'produtos' => $ids($this->request->getGet('tb_produtos')),
            ],
            'categoriasDisponiveis' => (new RefCategoria())->orderBy('categoria')->findAll(),
            'produtosDisponiveis' => $this->consultaProdutos()->findAll(),
            'comPeriodo' => $comPeriodo,
            'todo' => $todo,
            'inicio' => $inicio,
            'fim' => $fim,
            'periodoValido' => $periodoValido,
        ];
    }

    private function dataValida($valor): bool
    {
        return is_string($valor) && preg_match('/\A(\d{4})-(\d{2})-(\d{2})\z/', $valor, $partes)
            && checkdate((int) $partes[2], (int) $partes[3], (int) $partes[1]);
    }
}
