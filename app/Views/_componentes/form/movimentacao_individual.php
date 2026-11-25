<?php
use App\Libraries\PermissoesEstoque;
use App\Libraries\PoliticaCorrecoes;

$rota = 'restrito.' . $tipo;
$acao = $tipo === 'saida' ? 'saída' : 'entrada';
$campoData = 'data_' . $tipo;
$campoMotivo = 'id_motivo_' . $tipo;
$campoRegistro = 'id_produto_' . $tipo;
$idProduto = base64_encode((string) $produto['id_produto']);
$editando = !empty($registro[$campoRegistro]);
$hoje = date('Y-m-d');
$data = isset($registro[$campoData]) ? substr($registro[$campoData], 0, 10) : $hoje;
$saldo = (int) $produto['saldoEstoque'];
$saldoMinimo = (int) ($produto['estoque_minimo'] ?? 0);
$cor = trim((string) ($produto['cor_hexa'] ?? ''));
$corValida = preg_match('/\A#(?:[0-9a-f]{3}|[0-9a-f]{6})\z/i', $cor);
$historicoVisivel = PermissoesEstoque::tem($tipo . '.ver');
$podeCorrigir = $historicoVisivel && $historico && (new PoliticaCorrecoes())->permiteCorrigir();
$podeEditar = $podeCorrigir && PermissoesEstoque::tem($tipo . '.editar');
$podeExcluir = $podeCorrigir && PermissoesEstoque::tem($tipo . '.excluir');
$formatar = static fn ($valor): string => number_format((int) $valor, 0, ',', '.');
?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0"><?= $editando ? 'Editar ' . $acao : 'Registrar ' . $acao ?></h1>
        <p class="page-description"><?= $tipo === 'saida' ? 'Informe as unidades que serão retiradas do estoque.' : 'Informe as unidades que serão adicionadas ao estoque.' ?></p>
    </div>
    <?php if (PermissoesEstoque::tem('produto.ver')): ?>
        <a href="<?= url_to('restrito.produto.index') ?>" class="btn btn-outline-primary"><i class="fa fa-arrow-left me-2" aria-hidden="true"></i>Voltar aos produtos</a>
    <?php elseif ($historicoVisivel): ?>
        <a href="<?= url_to($rota . '.index') ?>" class="btn btn-outline-primary">Voltar ao histórico</a>
    <?php endif ?>
</header>

<section class="card movement-product" aria-labelledby="produto-movimentacao">
    <div class="card-body movement-summary">
        <div>
            <p class="text-muted small mb-1">Produto desta movimentação</p>
            <h2 id="produto-movimentacao" class="h5 mb-2"><?= esc($produto['nome']) ?></h2>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted small"><?= esc($produto['codigo']) ?> · <?= esc($produto['categoria']) ?></span>
                <?php if (!empty($produto['cor_nome'])): ?>
                    <span class="catalog-color mt-0">
                        <?php if ($corValida): ?><span class="color-swatch" style="background-color: <?= esc($cor, 'attr') ?>" aria-hidden="true"></span><?php endif ?>
                        <?= esc($produto['cor_nome']) ?>
                    </span>
                <?php endif ?>
            </div>
        </div>
        <dl class="movement-stock mb-0">
            <div><dt>Saldo atual</dt><dd class="<?= $saldo <= 0 ? 'text-danger' : '' ?>"><?= $formatar($saldo) ?> un.</dd></div>
            <div><dt>Estoque mínimo</dt><dd><?= $formatar($saldoMinimo) ?> un.</dd></div>
        </dl>
    </div>
</section>

<section class="card" aria-labelledby="titulo-dados-movimentacao">
    <div class="card-header"><h2 id="titulo-dados-movimentacao" class="card-title">Dados da <?= $acao ?></h2></div>
    <form method="post" action="<?= url_to($rota . '.salvar') ?>" id="movimentacao-individual"
          data-saldo="<?= $saldo ?>" data-anterior="<?= (int) ($registro['quantidade'] ?? 0) ?>" data-tipo="<?= $tipo ?>">
        <input type="hidden" name="id_produto" value="<?= (int) $produto['id_produto'] ?>">
        <?php if ($editando): ?>
            <input type="hidden" name="<?= $campoRegistro ?>" value="<?= base64_encode((string) $registro[$campoRegistro]) ?>">
        <?php endif ?>
        <div class="card-body">
            <?php if (!$locais || !$motivos): ?>
                <p class="form-guidance"><i class="fa fa-info-circle" aria-hidden="true"></i><span>Cadastre um local de estoque e um motivo de <?= $acao ?> em Configurações para continuar.</span></p>
            <?php endif ?>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label for="<?= $campoData ?>" class="form-label">Data da <?= $acao ?></label>
                    <input id="<?= $campoData ?>" name="<?= $campoData ?>" type="date" class="form-control <?= validation_show_error($campoData) ? 'is-invalid' : '' ?>"
                        value="<?= set_value($campoData, $data) ?>" max="<?= $hoje ?>" required>
                    <small class="text-danger"><?= validation_show_error($campoData) ?></small>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label for="quantidade" class="form-label">Quantidade <span class="text-muted">(un.)</span></label>
                    <input id="quantidade" name="quantidade" type="number" inputmode="numeric" min="1" max="2147483647" step="1" required
                        class="form-control <?= validation_show_error('quantidade') ? 'is-invalid' : '' ?>" value="<?= set_value('quantidade', $registro['quantidade'] ?? '') ?>">
                    <small class="text-danger"><?= validation_show_error('quantidade') ?></small>
                </div>
                <?php foreach ([
                    'id_local' => ['Local de estoque', 'local', $locais, 'Selecione um local'],
                    $campoMotivo => ['Motivo da ' . $acao, 'motivo_' . $tipo, $motivos, 'Selecione um motivo'],
                ] as $campo => [$rotulo, $chave, $opcoes, $placeholder]): ?>
                    <div class="col-sm-6 col-xl-3">
                        <label for="<?= $campo ?>" class="form-label"><?= esc($rotulo) ?></label>
                        <select id="<?= $campo ?>" name="<?= $campo ?>" class="form-select <?= validation_show_error($campo) ? 'is-invalid' : '' ?>" required>
                            <option value=""><?= esc($placeholder) ?></option>
                            <?php foreach ($opcoes as $opcao): ?>
                                <?php $id = (int) $opcao[$campo]; ?>
                                <option value="<?= $id ?>" <?= set_select($campo, (string) $id, (int) ($registro[$campo] ?? 0) === $id) ?>><?= esc($opcao[$chave]) ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-danger"><?= validation_show_error($campo) ?></small>
                    </div>
                <?php endforeach ?>
                <div class="col-12">
                    <label for="observacoes" class="form-label">Observações <span class="text-muted">(opcional)</span></label>
                    <textarea class="form-control <?= validation_show_error('observacoes') ? 'is-invalid' : '' ?>" id="observacoes" name="observacoes" rows="2" maxlength="500"><?= set_value('observacoes', $registro['observacoes'] ?? '') ?></textarea>
                    <small class="text-danger"><?= validation_show_error('observacoes') ?></small>
                </div>
            </div>
        </div>
        <div class="card-footer movement-footer">
            <output class="text-muted" for="quantidade" id="saldo-previsto" aria-live="polite"></output>
            <div class="d-flex flex-wrap gap-2">
                <?php if ($editando && PermissoesEstoque::tem($tipo . '.criar')): ?>
                    <a href="<?= url_to($rota . '.formulario', $idProduto) ?>" class="btn btn-outline-primary">Cancelar edição</a>
                <?php endif ?>
                <button type="submit" class="btn <?= $tipo === 'saida' ? 'catalog-out' : 'catalog-in' ?>"<?= !$locais || !$motivos ? ' disabled' : '' ?>>
                    <i class="fa fa-<?= $tipo === 'saida' ? 'arrow-up' : 'arrow-down' ?> me-2" aria-hidden="true"></i><?= $editando ? 'Salvar alterações' : 'Registrar ' . $acao ?>
                </button>
            </div>
        </div>
    </form>
</section>

<?php if ($historicoVisivel): ?>
<section class="card" aria-labelledby="titulo-historico-movimentacao">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h2 id="titulo-historico-movimentacao" class="card-title mb-0">Histórico de <?= $acao ?>s deste produto</h2>
        <?php if ($historico): ?>
            <small class="text-muted">Total registrado: <strong><?= $formatar(array_sum(array_column($historico, 'quantidade'))) ?> un.</strong></small>
        <?php endif ?>
    </div>
    <?php if (!$historico): ?>
        <div class="card-body"><p class="text-muted mb-0">Ainda não há <?= $acao ?>s registradas para este produto.</p></div>
    <?php else: ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($historico as $item): ?>
                <?php $id = (int) $item[$campoRegistro]; ?>
                <li class="list-group-item movement-record<?= $editando && $id === (int) $registro[$campoRegistro] ? ' movement-record-current' : '' ?>">
                    <div class="movement-record-info">
                        <strong><?= esc($item['motivo_' . $tipo] ?? 'Motivo não informado') ?></strong>
                        <small class="text-muted d-block"><?= esc(dataPTBR(substr($item[$campoData], 0, 10))) ?> · <?= esc($item['local'] ?? 'Local não informado') ?></small>
                        <?php if (!empty($item['usuarioCriacao'])): ?><small class="text-muted d-block">Registrado por <?= esc($item['usuarioCriacao']) ?></small><?php endif ?>
                        <?php if (!empty($item['observacoes'])): ?><small class="text-muted d-block"><?= esc($item['observacoes']) ?></small><?php endif ?>
                    </div>
                    <strong class="movement-record-quantity <?= $tipo === 'saida' ? 'text-danger' : 'text-success' ?>"><?= $tipo === 'saida' ? '−' : '+' ?><?= $formatar($item['quantidade']) ?> un.</strong>
                    <?php if ($podeEditar || $podeExcluir): ?>
                        <div class="d-flex gap-2">
                            <?php if ($podeEditar): ?><a class="btn btn-sm btn-outline-primary" href="<?= url_to($rota . '.editar', $idProduto, base64_encode((string) $id)) ?>">Editar</a><?php endif ?>
                            <?php if ($podeExcluir): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger modalExcluir" data-id-excluir="<?= $id ?>"
                                    data-url-excluir="<?= url_to($rota . '.excluir') ?>"
                                    data-mensagem-excluir="<?= esc('Confirma excluir a ' . $acao . ' de ' . $formatar($item['quantidade']) . ' un. deste produto?', 'attr') ?>">Excluir</button>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</section>
<?php endif ?>
