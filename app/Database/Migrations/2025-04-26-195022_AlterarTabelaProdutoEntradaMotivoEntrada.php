<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterarTabelaProdutoEntradaMotivoEntrada extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_produto_entrada', [
            'id_motivo_entrada' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'after'          => 'id_local',
            ],
        ]);

        $this->forge->addForeignKey('id_motivo_entrada', 'ref_motivo_entrada', 'id_motivo_entrada');
    }

    public function down()
    {
        $this->forge->dropColumn('tb_produto_entrada', 'id_motivo_entrada');
    }
}
