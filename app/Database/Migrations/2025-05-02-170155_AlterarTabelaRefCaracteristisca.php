<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterarTabelaRefCaracteristisca extends Migration
{
    public function up()
    {
        $this->forge->addColumn('ref_caracteristica', [
            'label' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'after'          => 'caracteristica',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('ref_caracteristica', 'label');
    }
}
