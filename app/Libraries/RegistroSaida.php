<?php

namespace App\Libraries;

use App\Models\TbProdutoSaida;
use RuntimeException;

class RegistroSaida
{
    public function salvar(array $saidas, ?int $idSaida = null): void
    {
        $quantidades = [];
        foreach ($saidas as $saida) {
            $idProduto = filter_var($saida['id_produto'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $quantidade = filter_var($saida['quantidade'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
            if ($idProduto === false || $quantidade === false) {
                throw new \DomainException('Informe um produto e uma quantidade inteira positiva em cada linha.');
            }
            $quantidades[$idProduto] = ($quantidades[$idProduto] ?? 0) + $quantidade;
        }
        if (!$quantidades) {
            throw new \DomainException('Adicione pelo menos um produto à saída.');
        }
        ksort($quantidades, SORT_NUMERIC);

        $db = db_connect();
        $model = new TbProdutoSaida($db);
        if (!$db->transBegin()) {
            throw new RuntimeException('Não foi possível iniciar o registro da saída.');
        }

        try {
            $produtos = $db->query(
                'SELECT id_produto, nome FROM ' . $db->protectIdentifiers('tb_produto', true)
                . ' WHERE id_produto IN ? AND deleted_at IS NULL ORDER BY id_produto FOR UPDATE',
                [array_keys($quantidades)]
            )->getResultArray();
            if (count($produtos) !== count($quantidades)) {
                throw new \DomainException('Um dos produtos selecionados está indisponível.');
            }

            $anterior = $idSaida === null ? null : $model->find($idSaida);
            if ($idSaida !== null && (!$anterior || !isset($quantidades[$anterior['id_produto']]))) {
                throw new \DomainException('A saída não pertence ao produto selecionado ou foi excluída.');
            }

            foreach ($produtos as $produto) {
                $idProduto = $produto['id_produto'];
                $saldo = (int) $db->query('SELECT fn_saldo_estoque(?) AS saldo', [$idProduto])->getRow()->saldo;
                if ($anterior && (int) $anterior['id_produto'] === (int) $idProduto) {
                    $saldo += (int) $anterior['quantidade'];
                }
                if ($quantidades[$idProduto] > $saldo) {
                    throw new SaldoInsuficiente($produto['nome'], $saldo, $quantidades[$idProduto]);
                }
            }

            foreach ($saidas as $saida) {
                if ($idSaida !== null) {
                    $saida['id_produto_saida'] = $idSaida;
                }
                if (!$model->save($saida)) {
                    throw new RuntimeException('Não foi possível gravar a saída.');
                }
            }

            if (!$db->transStatus() || !$db->transCommit()) {
                throw new RuntimeException('Não foi possível concluir o registro da saída.');
            }
        } catch (\Throwable $erro) {
            $db->transRollback();
            throw $erro;
        }
    }
}
