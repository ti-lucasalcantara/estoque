<?php

namespace App\Libraries;

class PoliticaCorrecoes
{
    private const TABELA = 'estoque_politica_correcoes';

    public function somenteAdministrador(): ?bool
    {
        $db = db_connect();
        if (!$db->tableExists(self::TABELA)) {
            return null;
        }

        return (bool) $db->table(self::TABELA)->select('somente_administrador')->where('id', 1)->get()->getRow('somente_administrador');
    }

    public function permiteCorrigir(): bool
    {
        return session('usuario_logado.cargo') === 'Administrador' || !$this->somenteAdministrador();
    }

    public function salvar(bool $somenteAdministrador): bool
    {
        return db_connect()->table(self::TABELA)->where('id', 1)
            ->update(['somente_administrador' => (int) $somenteAdministrador]);
    }
}
