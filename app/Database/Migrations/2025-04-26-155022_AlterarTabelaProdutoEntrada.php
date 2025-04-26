<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterarTabelaProdutoEntrada extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_produto_entrada', [
            'id_local' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'after'          => 'observacoes',
            ],
        ]);

        $this->forge->addForeignKey('id_local', 'ref_local', 'id_local');
    }

    public function down()
    {
        $this->forge->dropColumn('tb_produto_entrada', 'id_local');
    }
}
