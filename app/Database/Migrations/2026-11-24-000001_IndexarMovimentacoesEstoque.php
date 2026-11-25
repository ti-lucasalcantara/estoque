<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IndexarMovimentacoesEstoque extends Migration
{
    private const MOVIMENTACOES = [
        'entrada' => 'data_entrada',
        'saida' => 'data_saida',
    ];

    public function up()
    {
        foreach (self::MOVIMENTACOES as $tipo => $data) {
            $tabela = 'tb_produto_' . $tipo;
            $this->forge->addKey(['deleted_at', 'id_produto', $data], false, false, 'idx_' . $tipo . '_produto_ativo_data');
            $this->forge->addKey(['deleted_at', $data], false, false, 'idx_' . $tipo . '_ativo_data');
            if (!$this->forge->processIndexes($tabela)) {
                throw new \RuntimeException('Falha ao criar os índices de ' . $tabela);
            }
        }
    }

    public function down()
    {
        foreach (self::MOVIMENTACOES as $tipo => $data) {
            $tabela = 'tb_produto_' . $tipo;
            $this->forge->dropKey($tabela, 'idx_' . $tipo . '_ativo_data');
            $this->forge->dropKey($tabela, 'idx_' . $tipo . '_produto_ativo_data');
        }
    }
}
