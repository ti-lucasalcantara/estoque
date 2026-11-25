<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarPoliticaDeCorrecoes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'TINYINT', 'unsigned' => true],
            'somente_administrador' => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('estoque_politica_correcoes');
        $this->db->table('estoque_politica_correcoes')->insert(['id' => 1, 'somente_administrador' => 0]);
    }

    public function down()
    {
        $this->forge->dropTable('estoque_politica_correcoes');
    }
}
