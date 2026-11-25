<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<?php
$podeRegistrarEntrada = \App\Libraries\PermissoesEstoque::tem('entrada.criar');
$recebidas = array_sum($graficoEntradas);
$retiradas = array_sum($graficoSaidas);
$temMovimentacao = $recebidas || $retiradas;
$indicadores = [
    ['Produtos cadastrados', $totalProdutos ?? 0, 'cubes', false],
    ['Unidades recebidas', $recebidas, 'arrow-down', false],
    ['Unidades retiradas', $retiradas, 'arrow-up', false],
    ['Abaixo do mínimo', count($produtosCriticos), 'exclamation-circle', !empty($produtosCriticos)],
];
?>
<div class="dashboard">
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Visão geral do estoque</h1>
        <p class="page-description">Acompanhe a operação e identifique produtos que precisam de reposição.</p>
    </div>
    <div class="page-actions">
        <?php if ($podeRegistrarEntrada): ?><a class="btn btn-outline-primary" href="<?= url_to('restrito.entrada.formularioMultiplas') ?>"><i class="fa fa-arrow-down me-2" aria-hidden="true"></i>Registrar entrada</a><?php endif ?>
        <?php if (\App\Libraries\PermissoesEstoque::tem('saida.criar')): ?><a class="btn btn-primary" href="<?= url_to('restrito.saida.formularioMultiplas') ?>"><i class="fa fa-arrow-up me-2" aria-hidden="true"></i>Registrar saída</a><?php endif ?>
    </div>
</header>

<dl class="metric-grid mb-4">
    <?php foreach ($indicadores as [$rotulo, $valor, $icone, $atencao]): ?>
        <div class="card metric<?= $atencao ? ' metric-attention' : '' ?>">
            <dt><i class="fa fa-<?= $icone ?>" aria-hidden="true"></i><?= $rotulo ?></dt>
            <dd><?= number_format($valor, 0, ',', '.') ?></dd>
            <?php if ($icone === 'arrow-down' || $icone === 'arrow-up'): ?>
                <dd class="metric-period">Últimos 30 dias</dd>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</dl>

<section class="card" aria-labelledby="titulo-criticos">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="card-title mb-0" id="titulo-criticos">Produtos abaixo do mínimo</h2>
        <?php if (\App\Libraries\PermissoesEstoque::tem('produto.ver')): ?><a class="btn btn-sm btn-outline-primary" href="<?= url_to('restrito.produto.index') ?>">Abrir catálogo</a><?php endif ?>
    </div>
    <?php if ($produtosCriticos): ?>
        <div class="table-responsive" tabindex="0" role="region" aria-label="Produtos abaixo do mínimo">
            <table class="table">
                <thead><tr><th scope="col">Produto</th><th scope="col">Saldo</th><th scope="col">Mínimo</th><th scope="col">Faltam</th><?php if ($podeRegistrarEntrada): ?><th scope="col">Ação</th><?php endif ?></tr></thead>
                <tbody>
                    <?php foreach ($produtosCriticos as $produto): ?>
                        <tr>
                            <th scope="row"><strong><?= esc($produto['nome']) ?></strong><small class="d-block text-muted"><?= esc($produto['categoria']) ?> · <?= esc($produto['codigo']) ?> · <?= esc(getNomeCorProduto($produto)) ?></small></th>
                            <td><span class="stock-status <?= $produto['saldoEstoque'] <= 0 ? 'is-empty' : 'is-low' ?>"><?= esc($produto['saldoEstoque']) ?> un.</span></td>
                            <td><?= esc($produto['estoque_minimo']) ?> un.</td>
                            <td class="text-nowrap fw-semibold"><?= esc($produto['estoque_minimo'] - $produto['saldoEstoque']) ?> un.</td>
                            <?php if ($podeRegistrarEntrada): ?><td><a class="btn btn-sm btn-outline-primary text-nowrap" href="<?= url_to('restrito.entrada.formulario', base64_encode($produto['id_produto'])) ?>">Dar entrada</a></td><?php endif ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card-body"><p class="mb-0 text-muted">Nenhum produto abaixo do estoque mínimo.</p></div>
    <?php endif ?>
</section>

<section class="card" aria-labelledby="titulo-grafico">
    <div class="card-header">
        <h2 class="card-title mb-1" id="titulo-grafico">Movimentações nos últimos 30 dias</h2>
        <p class="text-muted small mb-0"><?= date('d/m/Y', strtotime($ultimos30Dias[0])) ?> a <?= date('d/m/Y', strtotime($ultimos30Dias[count($ultimos30Dias) - 1])) ?></p>
    </div>
    <div class="card-body">
        <?php if ($temMovimentacao): ?>
            <div class="chart-area">
                <canvas id="graficoMovimentacao" role="img" aria-label="Gráfico de unidades recebidas e retiradas por dia. Consulte a tabela abaixo para os valores."></canvas>
            </div>
            <details class="mt-3">
                <summary>Ver valores por dia</summary>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th scope="col">Data</th><th scope="col">Entradas</th><th scope="col">Saídas</th></tr></thead>
                        <tbody>
                            <?php foreach ($ultimos30Dias as $indice => $dia): ?>
                                <tr><td><?= date('d/m/Y', strtotime($dia)) ?></td><td><?= $graficoEntradas[$indice] ?></td><td><?= $graficoSaidas[$indice] ?></td></tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </details>
        <?php else: ?>
            <p class="text-muted mb-0">Nenhuma movimentação nos últimos 30 dias.</p>
        <?php endif ?>
    </div>
</section>

<div class="row">
    <?php foreach ([
        ['Produtos mais movimentados', $topProdutos, true],
        ['Produtos com mais saídas', $topProdutosSaidas, false],
    ] as [$titulo, $linhas, $comEntradas]): ?>
        <div class="col-xl-6">
            <section class="card">
                <div class="card-header"><h2 class="card-title"><?= $titulo ?></h2></div>
                <?php if ($linhas): ?>
                    <div class="table-responsive" tabindex="0" role="region" aria-label="<?= $titulo ?>">
                        <table class="table">
                            <thead><tr><th scope="col">Produto</th><?php if ($comEntradas): ?><th scope="col">Entradas</th><?php endif ?><th scope="col">Saídas</th></tr></thead>
                            <tbody>
                                <?php foreach ($linhas as $produto): ?>
                                    <tr>
                                        <td><?= esc($produto['nome']) ?><small class="d-block text-muted"><?= esc($produto['categoria']) ?> · <?= esc(getNomeCorProduto($produto)) ?></small></td>
                                        <?php if ($comEntradas): ?><td><?= esc($produto['entradas']) ?></td><?php endif ?>
                                        <td><?= esc($produto['saidas']) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="card-body"><p class="text-muted mb-0">Sem registros no período.</p></div>
                <?php endif ?>
            </section>
        </div>
    <?php endforeach ?>
</div>

<div class="row">
    <?php foreach ([
        ['Motivos de saída · 30 dias', $motivosSaidas30d, 'motivo_saida', 'total', 'un.'],
        ['Produtos por categoria', $categoriasQtd, 'categoria', 'qtd', 'prod.'],
    ] as [$titulo, $linhas, $rotulo, $valor, $unidade]): ?>
        <div class="col-lg-6">
            <section class="card">
                <div class="card-header"><h2 class="card-title"><?= $titulo ?></h2></div>
                <?php if ($linhas): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($linhas as $linha): ?>
                            <li class="list-group-item d-flex justify-content-between gap-2"><span><?= esc($linha[$rotulo]) ?></span><strong class="text-nowrap"><?= esc($linha[$valor]) ?> <?= $unidade ?></strong></li>
                        <?php endforeach ?>
                    </ul>
                <?php else: ?>
                    <div class="card-body"><p class="text-muted mb-0">Sem registros para exibir.</p></div>
                <?php endif ?>
            </section>
        </div>
    <?php endforeach ?>
</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?php if ($temMovimentacao): ?>
<script src="/assets/plugins/chart/chart.min.js"></script>
<script>
    new Chart(document.getElementById('graficoMovimentacao'), {
        type: 'line',
        data: {
            labels: <?= json_encode(array_map(static fn ($dia) => date('d/m', strtotime($dia)), $ultimos30Dias)) ?>,
            datasets: [
                {label: 'Entradas', data: <?= json_encode($graficoEntradas) ?>, borderColor: '#454545', pointRadius: 0, fill: false, lineTension: 0},
                {label: 'Saídas', data: <?= json_encode($graficoSaidas) ?>, borderColor: '#8b6018', borderDash: [5, 4], pointRadius: 0, fill: false, lineTension: 0}
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {duration: 0},
            tooltips: {mode: 'index', intersect: false},
            scales: {
                xAxes: [{gridLines: {display: false}, ticks: {maxTicksLimit: 7, maxRotation: 0}}],
                yAxes: [{ticks: {beginAtZero: true, precision: 0}}]
            }
        }
    });
</script>
<?php endif ?>
<?= $this->endSection() ?>
