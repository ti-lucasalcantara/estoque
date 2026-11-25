<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<?= $this->include('_componentes/form/ref_categoria_tb_produtos') ?>
<?php
$numero = static fn ($valor) => number_format((int) $valor, 0, ',', '.');
$totais = ['entrada' => 0, 'saida' => 0];
foreach ($movimentos as $linhas) {
    foreach ($linhas as $linha) {
        $totais[$linha['tipo']] += (int) $linha['quantidade'];
    }
}
?>
<?php if ($periodoValido && $produtos): ?>
    <dl class="report-metrics mb-3">
        <?php foreach (['Produtos consultados' => count($produtos), 'Unidades recebidas' => $totais['entrada'], 'Unidades retiradas' => $totais['saida'], 'Variação do saldo' => $totais['entrada'] - $totais['saida']] as $rotulo => $valor): ?>
            <div class="card metric"><dt><?= esc($rotulo) ?></dt><dd><?= $numero($valor) ?></dd></div>
        <?php endforeach ?>
    </dl>
    <p class="text-muted small mb-3"><?= $todo ? 'Todo o histórico' : esc(dataPTBR($inicio) . ' a ' . dataPTBR($fim)) ?></p>
    <?php foreach ($produtos as $posicao => $produto): ?>
        <?php
        $id = $produto['id_produto'];
        $linhas = $movimentos[$id] ?? [];
        $saldoInicial = $saldosIniciais[$id] ?? 0;
        $saldo = $saldoInicial;
        foreach ($linhas as &$linha) {
            $saldo += ($linha['tipo'] === 'entrada' ? 1 : -1) * (int) $linha['quantidade'];
            $linha['saldoApos'] = $saldo;
        }
        unset($linha);
        ?>
        <details class="card report-detail"<?= $posicao === 0 ? ' open' : '' ?>>
            <summary class="card-header">
                <span><strong><?= esc($produto['nome']) ?></strong>
                    <small class="text-muted d-block"><?= esc($produto['codigo'] . ' · ' . $produto['categoria'] . ($produto['cor_nome'] ? ' · ' . $produto['cor_nome'] : '')) ?></small>
                </span>
                <span class="text-muted small">Saldo final: <strong><?= $numero($saldo) ?> un.</strong></span>
            </summary>
            <div class="card-body py-2">
                <p class="mb-0 small text-muted">Saldo no início do período: <strong><?= $numero($saldoInicial) ?> un.</strong></p>
            </div>
            <?php if ($linhas): ?>
                <div class="table-responsive" tabindex="0" role="region" aria-label="<?= esc('Movimentações de ' . $produto['nome'], 'attr') ?>">
                    <table class="table report-table">
                        <thead><tr><th scope="col">Data</th><th scope="col">Tipo e motivo</th><th scope="col">Responsável</th><th scope="col" class="text-end">Quantidade</th><th scope="col" class="text-end">Saldo após</th></tr></thead>
                        <tbody>
                            <?php foreach (array_reverse($linhas) as $movimento): ?>
                                <?php $entrada = $movimento['tipo'] === 'entrada'; ?>
                                <tr>
                                    <td data-coluna="Data"><?= esc(dataPTBR(substr($movimento['data'], 0, 10))) ?></td>
                                    <td data-coluna="Tipo e motivo"><strong><?= $entrada ? 'Entrada' : 'Saída' ?></strong> · <?= esc($movimento['motivo'] ?? 'Motivo não informado') ?>
                                        <?php if (!empty($movimento['observacoes'])): ?><small class="d-block text-muted"><?= esc($movimento['observacoes']) ?></small><?php endif ?>
                                    </td>
                                    <td data-coluna="Responsável"><?= esc($movimento['usuario'] ?? '—') ?></td>
                                    <td data-coluna="Quantidade" class="text-end <?= $entrada ? 'text-success' : 'text-danger' ?>"><?= $entrada ? '+' : '−' ?><?= $numero($movimento['quantidade']) ?> un.</td>
                                    <td data-coluna="Saldo após" class="text-end fw-bold"><?= $numero($movimento['saldoApos']) ?> un.</td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="card-body text-muted mb-0">Nenhuma movimentação neste período.</p>
            <?php endif ?>
        </details>
    <?php endforeach ?>
<?php elseif ($periodoValido): ?>
    <p class="empty-state">Nenhum produto encontrado para os filtros aplicados.</p>
<?php endif ?>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="/assets/js/estoque-relatorios.js"></script>
<?= $this->endSection() ?>
