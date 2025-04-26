<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterarTabelaProdutoImagem extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_produto_imagem', [
            'principal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'thumb',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_produto_imagem', 'principal');
    }
}
