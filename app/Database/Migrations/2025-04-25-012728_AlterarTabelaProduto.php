<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterarTabelaProduto extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_produto', [
            'estoque_minimo' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'descricao',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_produto', 'estoque_minimo');
    }
}
