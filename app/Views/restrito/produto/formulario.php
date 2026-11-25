<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<?php
$produto = $produto ?? [];
$editando = !empty($produto['id_produto']);
$coresSelecionadas = array_map('strval', array_filter(
    (array) old('id_cor', $produto['id_cor'] ?? [], false),
    'is_scalar'
));
$definicoes = array_column($ref_caracteristica, null, 'id_caracteristica');
$extras = (array) json_decode($produto['json'] ?? '{}', true);
$valores = array_intersect_key((array) old('caracteristicas', $extras['caracteristicas'] ?? [], false), $definicoes);
$fotosIniciais = [];
foreach ($fotos ?? [] as $foto) {
    $meta = (array) json_decode($foto['json'] ?? '{}', true);
    $fotosIniciais[] = [
        'id' => (int) $foto['id_produto_imagem'],
        'url' => $foto['imagem'],
        'capa' => (bool) $foto['principal'],
        'ajustes' => \App\Libraries\GaleriaProduto::enquadramento($meta['enquadramento'] ?? null),
    ];
}
?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0"><?= $editando ? 'Editar produto' : 'Novo produto' ?></h1>
        <p class="page-description"><?= $editando ? 'Esta variação tem seu próprio saldo de estoque.' : 'Selecione uma ou mais cores. O sistema cria um produto com saldo próprio para cada cor.' ?></p>
    </div>
    <?php if (\App\Libraries\PermissoesEstoque::tem('produto.ver')): ?>
        <a href="<?= url_to('restrito.produto.index') ?>" class="btn btn-outline-primary">Voltar aos produtos</a>
    <?php endif ?>
</header>

<form id="formCadastroProduto" method="post" enctype="multipart/form-data" action="<?= url_to('restrito.produto.salvar') ?>">
    <?php if ($editando): ?>
        <input type="hidden" name="id_produto" value="<?= base64_encode((string) $produto['id_produto']) ?>">
    <?php endif ?>

    <div class="row g-3 align-items-start">
        <div class="col-12 col-lg-4">
            <section class="card" aria-labelledby="fotos-produto">
                <div class="card-header"><h2 class="card-title" id="fotos-produto">Fotos <small class="text-muted">(opcional)</small></h2></div>
                <div class="card-body">
                    <button id="area-fotos" class="photo-dropzone" type="button" aria-describedby="limites-fotos">
                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                        <span>Clique ou arraste fotos aqui</span>
                    </button>
                    <input id="selecionar-fotos" type="file" accept="image/jpeg,image/png,image/webp" multiple hidden>
                    <input id="fotos-envio" type="file" name="imagens[]" multiple hidden>
                    <noscript><p class="text-muted">Ative o JavaScript para adicionar fotos e características.</p></noscript>
                    <input id="galeria-envio" type="hidden" name="galeria" value="{}">
                    <div class="d-flex flex-wrap align-items-center gap-2 my-3">
                        <button id="adicionar-foto" class="btn btn-sm btn-outline-primary" type="button">Adicionar foto</button>
                        <small class="text-muted" id="limites-fotos">JPG, PNG ou WebP, até 8 MB por foto.</small>
                    </div>
                    <p class="text-danger small" id="erro-fotos" role="status" hidden></p>
                    <div id="preview-foto" hidden>
                        <div class="catalog-image photo-preview-frame" id="moldura-foto"><img id="imagem-preview" alt="" draggable="false"></div>
                        <p class="text-muted small mt-2 mb-1">Arraste a foto ou use os controles para ajustar o enquadramento.</p>
                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                            <strong class="text-break" id="titulo-foto"></strong>
                            <span class="stock-status" id="indicador-capa" hidden>Capa</span>
                        </div>
                        <div class="photo-thumbnails mb-3" id="galeria-fotos" role="group" aria-label="Selecionar foto para ajustar"></div>
                        <fieldset class="product-photo-controls">
                            <legend class="card-title">Enquadramento da foto selecionada</legend>
                            <label>Zoom <input class="form-range" type="range" data-ajuste="zoom" min="100" max="250"></label>
                            <label>Horizontal <input class="form-range" type="range" data-ajuste="x" min="0" max="100"></label>
                            <label>Vertical <input class="form-range" type="range" data-ajuste="y" min="0" max="100"></label>
                        </fieldset>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="definir-capa">Usar como capa</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="remover-foto">Remover foto</button>
                        </div>
                    </div>
                    <script id="fotos-iniciais" type="application/json"><?= json_encode($fotosIniciais, JSON_HEX_TAG | JSON_INVALID_UTF8_SUBSTITUTE) ?></script>
                </div>
            </section>
        </div>
        <div class="col-12 col-lg-8">
            <section class="card" aria-labelledby="identificacao-produto">
                <div class="card-header"><h2 class="card-title" id="identificacao-produto">Identificação e estoque</h2></div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <?php foreach ([
                            'codigo' => ['Código', 'text', 'col-md-3', 'Ex.: SOF-001-AZ'],
                            'nome' => ['Nome do produto', 'text', 'col-md-6', 'Ex.: Sofá retrátil 3 lugares'],
                            'estoque_minimo' => ['Estoque mínimo', 'number', 'col-md-3', 'Ex.: 2'],
                        ] as $campo => [$rotulo, $tipo, $coluna, $exemplo]): ?>
                            <?php $erro = validation_show_error($campo); ?>
                            <div class="<?= $coluna ?>">
                                <label class="form-label" for="<?= $campo ?>"><?= $rotulo ?></label>
                                <input class="form-control <?= $erro ? 'is-invalid' : '' ?>" id="<?= $campo ?>" name="<?= $campo ?>" type="<?= $tipo ?>"
                                    value="<?= set_value($campo, $produto[$campo] ?? '') ?>" placeholder="<?= $exemplo ?>" required
                                    <?= $tipo === 'number' ? 'min="0" max="2147483647" step="1" inputmode="numeric"' : 'maxlength="' . ($campo === 'codigo' ? 100 : 255) . '"' ?>>
                                <?php if ($erro): ?><small class="text-danger"><?= $erro ?></small><?php endif ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="row g-3 mb-3">
                        <?php foreach ([
                            'id_categoria' => ['Categoria', 'categoria', $ref_categoria],
                            'id_cor' => [$editando ? 'Cor' : 'Cores', 'nome', $ref_cor],
                        ] as $campo => [$rotulo, $chave, $opcoes]): ?>
                            <?php $variasCores = $campo === 'id_cor' && !$editando; ?>
                            <div class="col-md-6">
                                <label class="form-label" for="<?= $campo ?>"><?= $rotulo ?></label>
                                <select class="form-select" name="<?= $campo ?><?= $variasCores ? '[]' : '' ?>" id="<?= $campo ?>"
                                    <?= $variasCores ? 'multiple aria-describedby="orientacao-cores"' : '' ?> required>
                                    <?php if (!$variasCores): ?><option value="">Selecione</option><?php endif ?>
                                    <?php foreach ($opcoes as $opcao): ?>
                                        <?php $amostra = $campo === 'id_cor' ? trim((string) ($opcao['hexadecimal'] ?? '')) : ''; ?>
                                        <?php $selecionada = $campo === 'id_cor'
                                            ? (in_array((string) $opcao[$campo], $coresSelecionadas, true) ? 'selected' : '')
                                            : set_select($campo, (string) $opcao[$campo], ($produto[$campo] ?? '') == $opcao[$campo]); ?>
                                        <option value="<?= (int) $opcao[$campo] ?>" <?= $selecionada ?>
                                            <?php if ($amostra !== ''): ?>data-cor="<?= esc($amostra, 'attr') ?>"<?php endif ?>>
                                            <?= esc($opcao[$chave]) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                                <?php if ($variasCores): ?>
                                    <small class="text-muted d-block mt-1" id="orientacao-cores">O código, as características e as fotos são copiados para cada variação. Depois, você pode editar cada uma separadamente.</small>
                                    <small class="text-muted d-block" id="quantidade-variacoes" role="status"></small>
                                <?php endif ?>
                                <small class="text-danger"><?= validation_show_error($campo) ?></small>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <label class="form-label" for="descricao">Descrição <span class="text-muted">(opcional)</span></label>
                    <textarea id="descricao" name="descricao" class="form-control" maxlength="500" rows="3"><?= set_value('descricao', $produto['descricao'] ?? '') ?></textarea>
                    <small class="text-danger"><?= validation_show_error('descricao') ?></small>
                </div>
            </section>

            <section class="card" aria-labelledby="caracteristicas-produto">
                <div class="card-header"><h2 class="card-title" id="caracteristicas-produto">Características <small class="text-muted">(opcional)</small></h2></div>
                <div class="card-body">
                    <?php if ($definicoes): ?>
                        <p class="text-muted small">Adicione apenas as características deste produto, como material ou firmeza.</p>
                        <div id="caracteristicas-selecionadas"></div>
                        <template id="modelo-caracteristica">
                            <div class="product-attribute">
                                <label class="form-label mb-0">Característica
                                        <select class="form-select mt-1" data-select-caracteristica required>
                                        <option value="">Selecione</option>
                                        <?php foreach ($definicoes as $id => $definicao): ?>
                                            <option value="<?= (int) $id ?>"><?= esc($definicao['label'] ?: $definicao['caracteristica']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </label>
                                <label class="form-label mb-0">Valor
                                    <input class="form-control mt-1" type="text" maxlength="255" required>
                                </label>
                                <button type="button" class="btn btn-outline-danger" data-remover-caracteristica>Remover</button>
                            </div>
                        </template>
                        <button class="btn btn-outline-primary mt-2" type="button" id="adicionar-caracteristica">Adicionar característica</button>
                        <script id="valores-caracteristicas" type="application/json"><?= json_encode($valores, JSON_HEX_TAG | JSON_INVALID_UTF8_SUBSTITUTE) ?></script>
                    <?php else: ?>
                        <p class="text-muted mb-0">As características ficam disponíveis quando forem cadastradas em Configurações.</p>
                    <?php endif ?>
                </div>
            </section>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><?= $editando ? 'Salvar alterações' : 'Cadastrar produto' ?></button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="/assets/js/estoque-produto.js"></script>
<?= $this->endSection() ?>
