<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterarTabelaProdutoSaida extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_produto_saida', [
            'id_local' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'after'          => 'observacoes',
            ],
        ]);

        $this->forge->addColumn('tb_produto_saida', [
            'id_motivo_saida' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'after'          => 'observacoes',
            ],
        ]);
        
        $this->forge->addForeignKey('id_local', 'ref_local', 'id_local');
        $this->forge->addForeignKey('id_motivo_saida', 'ref_motivo_saida', 'id_motivo_saida');
    }

    public function down()
    {
        $this->forge->dropColumn('tb_produto_saida', 'id_local');
        $this->forge->dropColumn('tb_produto_saida', 'id_motivo_saida');
    }
}
