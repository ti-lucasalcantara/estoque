<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<?php
$destinos = array_column($configs, null, 'parametro');
$verCorrecoes = \App\Libraries\PermissoesEstoque::tem('config.correcoes');
$grupos = [
    'Catálogo' => [
        ['ref_categoria', 'Categorias', 'Organize os produtos por tipo.'],
        ['ref_cor', 'Cores', 'Defina as cores disponíveis no cadastro de produtos.'],
        ['ref_caracteristica', 'Características de produtos', 'Defina campos como material, tamanho e acabamento.'],
    ],
    'Movimentações' => [
        ['ref_local', 'Locais de estoque', 'Cadastre os locais usados nos lançamentos.'],
        ['ref_motivo_entrada', 'Motivos de entrada', 'Classifique as entradas por motivo.'],
        ['ref_motivo_saida', 'Motivos de saída', 'Classifique as saídas por motivo.'],
    ],
    'Acesso' => [
        ['tb_usuario', 'Usuários', 'Cadastre usuários e defina seus perfis de acesso.'],
    ],
];
?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Configurações</h1>
    </div>
</header>
<div class="settings-list">
    <?php foreach ($grupos as $titulo => $linhas): ?>
        <?php
        $linhas = array_filter($linhas, static fn ($linha) => isset($destinos[$linha[0]]));
        if (!$linhas && !($titulo === 'Movimentações' && $verCorrecoes)) {
            continue;
        }
        ?>
        <section class="settings-group">
            <h2><?= esc($titulo) ?></h2>
            <ul class="card list-unstyled">
                <?php foreach ($linhas as [$parametro, $rotulo, $descricao]): ?>
                    <?php $destino = $destinos[$parametro]; ?>
                    <li>
                        <a class="settings-link" href="<?= url_to($destino['rota'], base64_encode($destino['titulo']), base64_encode($parametro)) ?>">
                            <span>
                                <strong><?= esc($rotulo) ?></strong>
                                <small><?= esc($descricao) ?></small>
                            </span>
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </li>
                <?php endforeach ?>
                <?php if ($titulo === 'Movimentações' && $verCorrecoes): ?>
                    <li>
                        <a class="settings-link" href="<?= url_to('restrito.config.correcoes') ?>">
                            <span><strong>Regras de correção</strong><small>Defina quem pode editar ou excluir movimentações registradas.</small></span>
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </section>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>
