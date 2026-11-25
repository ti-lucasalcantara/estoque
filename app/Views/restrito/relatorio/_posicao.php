<?php
$minimo = $modo === 'minimo';
$poucasSaidas = $modo === 'giro';
$numero = static fn ($valor) => number_format((int) $valor, 0, ',', '.');
$quantidades = array_column($produtos, $poucasSaidas ? 'total_saidas' : 'saldoEstoque');
$abaixo = 0;
$faltam = 0;
foreach ($produtos as $produto) {
    if (!$poucasSaidas && (int) $produto['saldoEstoque'] < (int) $produto['estoque_minimo']) {
        $abaixo++;
        $faltam += (int) $produto['estoque_minimo'] - (int) $produto['saldoEstoque'];
    }
}
$metricas = match ($modo) {
    'minimo' => ['Produtos abaixo do mínimo' => count($produtos), 'Unidades para chegar ao mínimo' => $faltam],
    'giro' => ['Produtos exibidos' => count($produtos), 'Produtos sem saídas' => count(array_filter($quantidades, static fn ($n) => (int) $n === 0)), 'Unidades retiradas nos produtos exibidos' => array_sum($quantidades)],
    default => ['Produtos exibidos' => count($produtos), 'Unidades em estoque' => array_sum($quantidades), 'Produtos abaixo do mínimo' => $abaixo],
};
?>
<?php if ($produtos): ?>
    <dl class="report-metrics mb-3">
        <?php foreach ($metricas as $rotulo => $valor): ?>
            <div class="card metric"><dt><?= esc($rotulo) ?></dt><dd><?= $numero($valor) ?></dd></div>
        <?php endforeach ?>
    </dl>
    <section class="card" aria-labelledby="titulo-resultados">
        <div class="card-header"><h2 id="titulo-resultados" class="card-title">Resultados</h2></div>
        <div class="table-responsive" tabindex="0" role="region" aria-labelledby="titulo-resultados">
            <table class="table report-table">
                <thead><tr>
                    <th scope="col">Produto</th><th scope="col">Categoria</th>
                    <?php if ($poucasSaidas): ?>
                        <th scope="col" class="text-end">Unidades retiradas</th><th scope="col">Movimentação</th>
                    <?php else: ?>
                        <th scope="col" class="text-end">Saldo atual</th><th scope="col" class="text-end">Estoque mínimo</th>
                        <th scope="col"<?= $minimo ? ' class="text-end"' : '' ?>><?= $minimo ? 'Faltam para o mínimo' : 'Situação' ?></th>
                    <?php endif ?>
                </tr></thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <?php
                        $quantidade = (int) $produto[$poucasSaidas ? 'total_saidas' : 'saldoEstoque'];
                        $estoqueMinimo = (int) ($produto['estoque_minimo'] ?? 0);
                        $cor = (string) ($produto['cor_hex'] ?? '');
                        ?>
                        <tr>
                            <td data-coluna="Produto">
                                <strong><?= esc($produto['nome']) ?></strong>
                                <small class="text-muted d-block"><?= esc($produto['codigo']) ?>
                                    <?php if ($produto['cor_nome']): ?>
                                        · <?php if (preg_match('/\A#(?:[0-9a-f]{3}|[0-9a-f]{6})\z/i', $cor)): ?><span class="color-swatch" style="background-color: <?= esc($cor, 'attr') ?>" aria-hidden="true"></span><?php endif ?><?= esc($produto['cor_nome']) ?>
                                    <?php endif ?>
                                </small>
                            </td>
                            <td data-coluna="Categoria"><?= esc($produto['categoria']) ?></td>
                            <?php if ($poucasSaidas): ?>
                                <td data-coluna="Unidades retiradas" class="text-end"><strong><?= $numero($quantidade) ?> un.</strong></td>
                                <td data-coluna="Movimentação"><?= $quantidade === 0 ? '<span class="stock-status is-low">Sem saídas</span>' : 'Com saídas' ?></td>
                            <?php else: ?>
                                <td data-coluna="Saldo atual" class="text-end"><strong><?= $numero($quantidade) ?> un.</strong></td>
                                <td data-coluna="Estoque mínimo" class="text-end"><?= $numero($estoqueMinimo) ?> un.</td>
                                <?php if ($minimo): ?>
                                    <td data-coluna="Faltam para o mínimo" class="text-danger text-end fw-bold"><?= $numero($estoqueMinimo - $quantidade) ?> un.</td>
                                <?php else: ?>
                                    <td data-coluna="Situação">
                                        <?php if ($quantidade <= 0): ?><span class="stock-status is-empty">Sem estoque</span>
                                        <?php elseif ($quantidade < $estoqueMinimo): ?><span class="stock-status is-low">Abaixo do mínimo</span>
                                        <?php else: ?><span class="stock-status">Disponível</span><?php endif ?>
                                    </td>
                                <?php endif ?>
                            <?php endif ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
<?php elseif ($periodoValido): ?>
    <p class="empty-state"><?= $minimo ? 'Nenhum produto abaixo do mínimo para os filtros aplicados.' : 'Nenhum produto encontrado para os filtros aplicados.' ?></p>
<?php endif ?>
