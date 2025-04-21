<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarFuncaoSaldoEstoque extends Migration
{
    public function up()
    {
        $this->db->query("
            DROP FUNCTION IF EXISTS fn_saldo_estoque;
        ");

        $this->db->query("
            CREATE FUNCTION fn_saldo_estoque(p_id_produto INT)
            RETURNS INT
            DETERMINISTIC
            BEGIN
                DECLARE total_entrada INT DEFAULT 0;
                DECLARE total_saida INT DEFAULT 0;
                DECLARE saldo INT;

                SELECT IFNULL(SUM(quantidade), 0)
                INTO total_entrada
                FROM tb_produto_entrada
                WHERE id_produto = p_id_produto 
                AND deleted_at IS NULL;

                SELECT IFNULL(SUM(quantidade), 0)
                INTO total_saida
                FROM tb_produto_saida
                WHERE id_produto = p_id_produto
                AND deleted_at IS NULL;

                SET saldo = total_entrada - total_saida;

                RETURN saldo;
            END
        ");
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION IF EXISTS fn_saldo_estoque;");
    }
}
