<?php

namespace App\Libraries;

use App\Models\TbProdutoEntrada;
use App\Models\TbProdutoSaida;

class HistoricoMovimentacoes
{
    public static function consultar(string $tipo, $inicio, $fim): array
    {
        $hoje = date('Y-m-d');
        $inicio ??= date('Y-m-01');
        $fim ??= $hoje;
        $periodoValido = self::dataValida($inicio) && self::dataValida($fim) && $inicio <= $fim && $fim <= $hoje;
        if (!$periodoValido) {
            $inicio = date('Y-m-01');
            $fim = $hoje;
        }

        $model = $tipo === 'entrada' ? new TbProdutoEntrada() : new TbProdutoSaida();
        $tabela = 'tb_produto_' . $tipo;
        $campoData = $tabela . '.data_' . $tipo;
        $motivo = 'motivo_' . $tipo;
        $refMotivo = 'ref_' . $motivo;
        $registros = $model
            ->select("$tabela.*, tb_produto.nome AS produto, tb_produto.codigo, ref_categoria.categoria,
                      ref_cor.nome AS cor_nome, ref_cor.hexadecimal AS cor_hex, $refMotivo.$motivo, ref_local.local")
            ->join('tb_produto', "tb_produto.id_produto = $tabela.id_produto")
            ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
            ->join('ref_cor', 'ref_cor.id_cor = tb_produto.id_cor', 'left')
            ->join('ref_local', "ref_local.id_local = $tabela.id_local", 'left')
            ->join($refMotivo, "$refMotivo.id_$motivo = $tabela.id_$motivo", 'left')
            ->where($campoData . ' >=', $inicio)
            ->where($campoData . ' <=', $fim . ' 23:59:59')
            ->orderBy($campoData, 'DESC')
            ->orderBy($tabela . '.id_produto_' . $tipo, 'DESC')
            ->findAll();

        return ['registros' => $registros, 'data_inicio' => $inicio, 'data_fim' => $fim, 'periodoValido' => $periodoValido];
    }

    private static function dataValida($valor): bool
    {
        if (!is_string($valor) || !preg_match('/\A(\d{4})-(\d{2})-(\d{2})\z/', $valor, $partes)) {
            return false;
        }
        return checkdate((int) $partes[2], (int) $partes[3], (int) $partes[1]);
    }
}
