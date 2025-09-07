<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterarTabelaProdutoCor extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_produto', [
            'id_cor' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'after'          => 'id_categoria',
            ],
        ]);

        $this->forge->addForeignKey('id_cor', 'ref_cor', 'id_cor');
    }

    public function down()
    {
        $this->forge->dropColumn('tb_produto', 'id_cor');
    }
}
