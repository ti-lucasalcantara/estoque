<?php

namespace App\Libraries;

use DomainException;

class SaldoInsuficiente extends DomainException
{
    public function __construct(
        public readonly string $produto,
        public readonly int $disponivel,
        public readonly int $solicitado,
    ) {
        parent::__construct('Saldo insuficiente para registrar a saída.');
    }
}
