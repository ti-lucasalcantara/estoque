<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CriarTabelaProdutoImagem extends Migration
{
    private $table = 'tb_produto_imagem';

    public function up()
    {
        $this->forge->addField([
            'id_produto_imagem' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_produto' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'imagem' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'thumb' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'json' => [
                'type'       => 'JSON',
                'null'  => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type'    => 'TIMESTAMP',
                'null' => true,
            ],
            'user_created' => [
                'type'        => 'INT',
                'constraint'  => 11,
                'unsigned'    => true,
                'null'        => true,
            ],
            'user_updated' => [
                'type'        => 'INT',
                'constraint'  => 11,
                'unsigned'    => true,
                'null'        => true,
            ],
            'user_deleted' => [
                'type'        => 'INT',
                'constraint'  => 11,
                'unsigned'    => true,
                'null'        => true,
            ],
        ]);

        $this->forge->addKey('id_produto_imagem', true);
        $this->forge->addForeignKey('user_created', 'tb_usuario', 'id_usuario');
        $this->forge->addForeignKey('user_updated', 'tb_usuario', 'id_usuario');
        $this->forge->addForeignKey('user_deleted', 'tb_usuario', 'id_usuario');
        
        $this->forge->addForeignKey('id_produto', 'tb_produto', 'id_produto');
        
        $this->forge->createTable($this->table, false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
