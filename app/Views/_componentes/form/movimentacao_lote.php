<?php
$saida = $tipo === 'saida';
$acao = $saida ? 'saída' : 'entrada';
$campoData = 'data_' . $tipo;
$campoMotivo = 'id_motivo_' . $tipo;
$rota = 'restrito.' . $tipo;
$linhas = [];
foreach ((array) old('produtos', [], false) as $linha) {
    foreach ((array) ($linha['id_produto'] ?? ['']) as $id) {
        $linhas[] = [$id, $linha['quantidade'] ?? ''];
    }
}
$linhas = $linhas ?: [['', '']];
$opcoes = [];
foreach ($produtos as $produto) {
    $cor = $produto['cor_nome'];
    $opcoes[$produto['id_produto']] = [
        'rotulo' => $produto['nome'] . ' · ' . $produto['codigo'] . ($cor ? ' · ' . $cor : ''),
        'cor' => $produto['cor_hex'] ?? '',
    ];
}
$renderLinha = static function ($indice, $id, $quantidade) use ($opcoes) {
    $prefixo = 'produtos[' . $indice . ']';
    ?>
    <div class="batch-line" data-batch-line>
        <div>
            <label for="produto-<?= $indice ?>" class="form-label">Produto</label>
            <select name="<?= $prefixo ?>[id_produto][]" id="produto-<?= $indice ?>" class="form-select" data-batch-product required>
                <option value="">Selecione um produto</option>
                <?php foreach ($opcoes as $valor => $opcao): ?>
                    <option value="<?= esc($valor, 'attr') ?>" data-cor="<?= esc($opcao['cor'], 'attr') ?>"
                        <?= (string) $valor === (string) $id ? 'selected' : '' ?>><?= esc($opcao['rotulo']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div>
            <label for="quantidade-<?= $indice ?>" class="form-label">Quantidade</label>
            <input id="quantidade-<?= $indice ?>" name="<?= $prefixo ?>[quantidade]" class="form-control" type="number" inputmode="numeric" min="1" step="1" value="<?= esc($quantidade, 'attr') ?>" required>
        </div>
        <button type="button" class="btn btn-outline-danger" data-batch-remove>Remover</button>
    </div>
    <?php
};
?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Registrar <?= $acao ?> em lote</h1>
        <p class="page-description">Informe os dados da movimentação e adicione uma linha por produto.</p>
    </div>
    <a href="<?= url_to($rota . '.index') ?>" class="btn btn-outline-primary">Voltar ao histórico</a>
</header>
<?php if (!$opcoes || !$locais || !$motivos): ?>
    <p class="empty-state">Para registrar uma <?= $acao ?>, cadastre um produto, um local e um motivo de <?= $acao ?>.</p>
<?php endif ?>
<form method="post" action="<?= url_to($rota . '.salvarMultiplas') ?>" id="form-lote">
    <section class="card">
        <div class="card-header"><h2 class="card-title">Dados da movimentação</h2></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="<?= $campoData ?>" class="form-label">Data</label>
                    <input type="date" name="<?= $campoData ?>" id="<?= $campoData ?>" class="form-control <?= validation_show_error($campoData) ? 'is-invalid' : '' ?>" value="<?= set_value($campoData, date('Y-m-d')) ?>" max="<?= date('Y-m-d') ?>" required>
                    <small class="text-danger"><?= validation_show_error($campoData) ?></small>
                </div>
                <div class="col-md-4">
                    <label for="id_local" class="form-label">Local</label>
                    <select id="id_local" name="id_local" class="form-select <?= validation_show_error('id_local') ? 'is-invalid' : '' ?>" data-lote-select required>
                        <option value="">Selecione um local</option>
                        <?php foreach ($locais as $local): ?>
                            <option value="<?= esc($local['id_local'], 'attr') ?>" <?= set_select('id_local', $local['id_local']) ?>><?= esc($local['local']) ?></option>
                        <?php endforeach ?>
                    </select>
                    <small class="text-danger"><?= validation_show_error('id_local') ?></small>
                </div>
                <div class="col-md-4">
                    <label for="<?= $campoMotivo ?>" class="form-label">Motivo</label>
                    <select id="<?= $campoMotivo ?>" name="<?= $campoMotivo ?>" class="form-select <?= validation_show_error($campoMotivo) ? 'is-invalid' : '' ?>" data-lote-select required>
                        <option value="">Selecione um motivo</option>
                        <?php foreach ($motivos as $motivo): ?>
                            <option value="<?= esc($motivo[$campoMotivo], 'attr') ?>" <?= set_select($campoMotivo, $motivo[$campoMotivo]) ?>><?= esc($motivo['motivo_' . $tipo]) ?></option>
                        <?php endforeach ?>
                    </select>
                    <small class="text-danger"><?= validation_show_error($campoMotivo) ?></small>
                </div>
                <div class="col-12">
                    <label for="observacoes" class="form-label">Observações <span class="text-muted">(opcional)</span></label>
                    <textarea name="observacoes" id="observacoes" class="form-control" maxlength="500"><?= set_value('observacoes') ?></textarea>
                    <small class="text-danger"><?= validation_show_error('observacoes') ?></small>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-header"><h2 class="card-title">Produtos</h2></div>
        <div class="card-body">
            <div id="lote-linhas">
                <?php foreach ($linhas as $indice => [$id, $quantidade]): ?>
                    <?php $renderLinha($indice, $id, $quantidade); ?>
                <?php endforeach ?>
            </div>
            <template id="lote-modelo"><?php $renderLinha('INDICE', '', ''); ?></template>
            <button type="button" id="lote-adicionar" class="btn btn-outline-primary"<?= !$opcoes ? ' disabled' : '' ?>><i class="fa fa-plus me-2" aria-hidden="true"></i>Adicionar produto</button>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
            <output id="lote-resumo" class="text-muted" aria-live="polite"></output>
            <button type="submit" id="lote-salvar" class="btn btn-primary"<?= !$opcoes || !$locais || !$motivos ? ' disabled' : '' ?>>Registrar <?= $acao ?></button>
        </div>
    </section>
</form>
