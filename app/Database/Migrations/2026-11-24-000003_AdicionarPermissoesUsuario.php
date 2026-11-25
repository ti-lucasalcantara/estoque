<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionarPermissoesUsuario extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_usuario', [
            'permissoes' => ['type' => 'JSON', 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_usuario', 'permissoes');
    }
}
