<?php

namespace App\Libraries;

class PermissoesEstoque
{
    public const GRUPOS = [
        'Visão geral' => ['dashboard.ver' => 'Ver painel'],
        'Produtos' => [
            'produto.ver' => 'Ver catálogo', 'produto.criar' => 'Cadastrar produtos',
            'produto.editar' => 'Editar produtos', 'produto.excluir' => 'Excluir produtos',
        ],
        'Entradas' => [
            'entrada.ver' => 'Ver histórico de entradas', 'entrada.criar' => 'Cadastrar entradas',
            'entrada.editar' => 'Editar entradas', 'entrada.excluir' => 'Excluir entradas',
        ],
        'Saídas' => [
            'saida.ver' => 'Ver histórico de saídas', 'saida.criar' => 'Cadastrar saídas',
            'saida.editar' => 'Editar saídas', 'saida.excluir' => 'Excluir saídas',
        ],
        'Relatórios' => ['relatorio.ver' => 'Ver relatórios'],
        'Configurações' => [
            'config.ver' => 'Consultar configurações do estoque',
            'config.correcoes' => 'Consultar e alterar regras de correção',
        ],
    ];

    private const CADASTROS = [
        'ref_categoria' => 'Categorias',
        'ref_cor' => 'Cores',
        'ref_caracteristica' => 'Características de produtos',
        'ref_local' => 'Locais de estoque',
        'ref_motivo_entrada' => 'Motivos de entrada',
        'ref_motivo_saida' => 'Motivos de saída',
    ];

    public const LEGADO = [
        'dashboard.ver', 'produto.ver', 'produto.criar', 'produto.editar', 'produto.excluir',
        'entrada.ver', 'entrada.criar', 'entrada.editar', 'entrada.excluir',
        'saida.ver', 'saida.criar', 'saida.editar', 'saida.excluir',
    ];

    public static function permitidas(): array
    {
        return array_keys(array_merge(...array_values(self::grupos())));
    }

    public static function grupos(): array
    {
        $grupos = self::GRUPOS;
        foreach (self::CADASTROS as $tabela => $nome) {
            $prefixo = 'config.' . $tabela;
            $grupos['Configurações · ' . $nome] = [
                $prefixo . '.ver' => 'Consultar',
                $prefixo . '.editar' => 'Cadastrar e editar',
                $prefixo . '.excluir' => 'Excluir',
            ];
        }
        return $grupos;
    }

    public static function selecionadas(?string $gravadas): array
    {
        return $gravadas === null ? self::LEGADO : (array) json_decode($gravadas, true);
    }

    public static function tem(string $permissao): bool
    {
        return session('usuario_logado.cargo') === 'Administrador'
            || in_array($permissao, self::selecionadas(session('usuario_logado.permissoes')), true);
    }

    public static function cadastro(string $tabela, string $acao): bool
    {
        if (session('usuario_logado.cargo') === 'Administrador') {
            return true;
        }
        if (!isset(self::CADASTROS[$tabela])) {
            return false;
        }
        if ($acao !== 'ver') {
            return self::tem('config.' . $tabela . '.' . $acao);
        }
        return self::tem('config.ver') || self::tem('config.' . $tabela . '.ver')
            || self::cadastro($tabela, 'editar') || self::cadastro($tabela, 'excluir');
    }

    public static function podeAbrirConfiguracoes(): bool
    {
        if (self::tem('config.ver') || self::tem('config.correcoes')) {
            return true;
        }
        foreach (array_keys(self::CADASTROS) as $tabela) {
            if (self::cadastro($tabela, 'ver')) {
                return true;
            }
        }
        return false;
    }

    public static function destinoInicial(): string
    {
        foreach ([
            'dashboard.ver' => 'restrito.dashboard.index',
            'produto.ver' => 'restrito.produto.index',
            'entrada.ver' => 'restrito.entrada.index',
            'entrada.criar' => 'restrito.entrada.formularioMultiplas',
            'saida.ver' => 'restrito.saida.index',
            'saida.criar' => 'restrito.saida.formularioMultiplas',
            'relatorio.ver' => 'restrito.relatorio.index',
            'config.ver' => 'restrito.config.index',
            'produto.criar' => 'restrito.produto.formulario',
        ] as $permissao => $rota) {
            if (self::tem($permissao)) {
                return $rota;
            }
        }

        return self::podeAbrirConfiguracoes() ? 'restrito.config.index' : 'restrito.resetar-senha';
    }
}
