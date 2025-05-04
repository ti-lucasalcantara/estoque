<?=$this->extend('restrito/template/principal')?>

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Dashboard</h4>
    </div>
</div>

<!-- KPIs Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h6>Total de Produtos</h6>
                <h3><?= $totalProdutos ?? 0 ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h6>Entradas (30 dias)</h6>
                <h3><?= array_sum($graficoEntradas) ?? 0 ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-danger text-white">
            <div class="card-body">
                <h6>Saídas (30 dias)</h6>
                <h3><?= array_sum($graficoSaidas) ?? 0 ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-warning text-white">
            <div class="card-body">
                <h6>Produtos abaixo do estoque</h6>
                <h3><?= count($produtosCriticos) ?? 0 ?></h3>
            </div>
        </div>
    </div>
</div>


<!-- Alertas -->
<div class="card mt-4 <?=(!empty($produtosCriticos)) ? 'border-danger' : 'border-success'?>">
    <div class="card-header <?=(!empty($produtosCriticos)) ? 'bg-danger' : 'bg-success'?> text-white">
        <h5>⚠️ Produtos abaixo do estoque mínimo</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($produtosCriticos)) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Estoque Atual</th>
                            <th>Estoque Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;
                        foreach ($produtosCriticos as $p) :
                        ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?= esc($p['codigo']) ?></td>
                                <td>
                                    <span style="display:inline-block;width:15px;height:15px;background-color:<?=getHexaCorProduto($p)?>;margin-right:5px;border:1px solid #000;border-radius:3px;"></span>
                                    <?= esc($p['nome']) ?>
                                    <small class="text-muted">(<?=getNomeCorProduto($p)?>)</small>
                                </td>
                                <td><?= esc($p['categoria'] ?? '') ?></td>
                                <td class="text-danger"><strong><?= esc($p['saldoEstoque']) ?></strong></td>
                                <td><?= esc($p['estoque_minimo']) ?></td>
                            </tr>
                        <?php
                        $i++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="text-success">✔️ Nenhum produto abaixo do mínimo. Estoque saudável!</p>
        <?php endif; ?>
    </div>
</div>

<!-- Gráfico (placeholder) -->
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5>📊 Movimentação dos últimos 30 dias</h5>
    </div>
    <div class="card-body">
        <canvas id="graficoMovimentacao"></canvas>
    </div>
</div>


<div class="card mt-4">
    <div class="card-header bg-secondary text-white">
        <h5>📦 Motivos de Saída (últimos 30 dias)</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($motivosSaidas30d)) : ?>
            <ul class="list-group">
                <?php foreach ($motivosSaidas30d as $motivo) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($motivo['motivo_saida']) ?>
                        <span class="badge border-primary text-dark col-1" style="font-size: 12px;"><?= esc($motivo['total']) ?> itens</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p class="text-muted">Sem saídas registradas nos últimos 30 dias.</p>
        <?php endif; ?>
    </div>
</div>


<!-- Top produtos -->
<div class="card mt-4">
    <div class="card-header bg-success text-white">
        <h5>🏆 Top 5 Produtos Mais Movimentados</h5>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Entradas</th>
                    <th>Saídas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($topProdutos) && !empty($topProdutos)):
                    foreach ($topProdutos as $p) : ?>
                    <tr>
                        <td>
                            <span style="display:inline-block;width:15px;height:15px;background-color:<?=getHexaCorProduto($p)?>;margin-right:5px;border:1px solid #000;border-radius:3px;"></span>
                            <?= esc($p['nome']) ?>
                            <small class="text-muted">(<?=getNomeCorProduto($p)?>)</small>
                        </td>
                        <td><?= esc($p['categoria']) ?></td>
                        <td><?= esc($p['entradas']) ?></td>
                        <td><?= esc($p['saidas']) ?></td>
                    </tr>
                    <?php 
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Quantitativo por Categoria -->
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5>Produtos por Categoria</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($categoriasQtd)) : ?>
            <ul class="list-group">
                <?php foreach ($categoriasQtd as $cat) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($cat['categoria']) ?>
                        <span class="badge border-primary text-dark rounded-pill" style="font-size: 12px;"><?= esc($cat['qtd']) ?> produtos</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>⚠️ Nenhuma categoria cadastrada.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
.card h3 {
    margin: 0;
    font-size: 2rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/plugins/chart/chart.min.js"></script>

<script>
$(document).ready(function() {
    const ctx = document.getElementById('graficoMovimentacao').getContext('2d');
    const grafico = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($ultimos30Dias) ?>,
            datasets: [
                {
                    label: 'Entradas',
                    data: <?= json_encode($graficoEntradas) ?>,
                    borderColor: 'green',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Saídas',
                    data: <?= json_encode($graficoSaidas) ?>,
                    borderColor: 'red',
                    fill: false,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
