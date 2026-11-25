<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarAcessosLembrados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'token_hash' => ['type' => 'CHAR', 'constraint' => 64],
            'id_usuario' => ['type' => 'INT', 'unsigned' => true],
            'senha_hash' => ['type' => 'CHAR', 'constraint' => 64],
            'expira_em' => ['type' => 'BIGINT', 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey('token_hash');
        $this->forge->addKey('expira_em');
        $this->forge->addForeignKey('id_usuario', 'tb_usuario', 'id_usuario', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_acesso_lembrado');
    }

    public function down()
    {
        $this->forge->dropTable('tb_acesso_lembrado');
    }
}
