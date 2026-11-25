<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Produtos</h1>
        <p class="page-description">Consulte o saldo e registre movimentações pelo catálogo.</p>
    </div>
    <?php if (\App\Libraries\PermissoesEstoque::tem('produto.criar')): ?><a href="<?= url_to('restrito.produto.formulario') ?>" class="btn btn-primary"><i class="fa fa-plus me-2" aria-hidden="true"></i>Novo produto</a><?php endif ?>
</header>
<?php if (empty($tb_produto)): ?>
    <p class="empty-state">Nenhum produto cadastrado.</p>
<?php else: ?>
    <?php
    $produtos = $tb_produto;
    $categorias = array_column($produtos, 'categoria', 'id_categoria');
    $cores = [];
    foreach ($produtos as $produto) {
        if ($produto['cor_nome']) {
            $cores[$produto['id_cor']] = $produto['cor_nome'];
        } else {
            $cores[0] = 'Sem cor cadastrada';
        }
    }
    asort($categorias, SORT_NATURAL | SORT_FLAG_CASE);
    asort($cores, SORT_NATURAL | SORT_FLAG_CASE);
    $podeEditar = \App\Libraries\PermissoesEstoque::tem('produto.editar');
    $podeExcluir = \App\Libraries\PermissoesEstoque::tem('produto.excluir');
    $podeEntrada = \App\Libraries\PermissoesEstoque::tem('entrada.criar');
    $podeSaida = \App\Libraries\PermissoesEstoque::tem('saida.criar');
    $status = static function (array $produto): array {
        if ((int) $produto['saldoEstoque'] <= 0) {
            return ['is-empty', 'Sem estoque'];
        }
        if ((int) $produto['saldoEstoque'] < (int) $produto['estoque_minimo']) {
            return ['is-low', 'Abaixo do mínimo'];
        }
        return ['', 'Disponível'];
    };
    $busca = static fn (array $produto): string => implode(' ', [$produto['nome'], $produto['codigo'], $produto['cor_nome'], $produto['categoria']]);
    ?>
    <form class="filter-panel" id="filtros-catalogo" role="search" aria-label="Filtrar catálogo">
        <label for="filtro-produto" class="form-label">Buscar produto</label>
        <input id="filtro-produto" name="busca" type="search" class="form-control" placeholder="Nome, código, cor ou categoria">
        <details class="catalog-advanced mt-3">
            <summary>Filtros avançados <span class="stock-status ms-2" id="filtros-ativos" hidden></span></summary>
            <div class="row g-3 mt-1">
                <?php foreach ([
                    'categoria' => ['Categoria', 'Todas as categorias', $categorias],
                    'cor' => ['Cor', 'Todas as cores', $cores],
                    'situacao' => ['Situação do estoque', 'Todas as situações', [
                        'disponivel' => 'Disponível', 'is-low' => 'Abaixo do mínimo', 'is-empty' => 'Sem estoque',
                    ]],
                ] as $campo => [$rotulo, $padrao, $opcoes]): ?>
                    <div class="col-sm-6 col-xl-4">
                        <label for="filtro-<?= $campo ?>" class="form-label"><?= $rotulo ?></label>
                        <select class="form-select" id="filtro-<?= $campo ?>" name="<?= $campo ?>">
                            <option value=""><?= $padrao ?></option>
                            <?php foreach ($opcoes as $valor => $nome): ?>
                                <option value="<?= esc((string) $valor, 'attr') ?>"><?= esc($nome) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endforeach ?>
                <div class="col-sm-6 col-xl-4">
                    <label for="saldo-de" class="form-label">Saldo de (unidades)</label>
                    <input class="form-control" id="saldo-de" name="saldo_de" type="number" step="1" aria-describedby="erro-faixa-saldo">
                </div>
                <div class="col-sm-6 col-xl-4">
                    <label for="saldo-ate" class="form-label">Saldo até (unidades)</label>
                    <input class="form-control" id="saldo-ate" name="saldo_ate" type="number" step="1" aria-describedby="erro-faixa-saldo">
                </div>
            </div>
            <p class="text-danger small mt-2 mb-0" id="erro-faixa-saldo" role="status" hidden>O saldo inicial deve ser menor ou igual ao saldo final.</p>
        </details>
    </form>
    <div class="d-flex align-items-center gap-2 mb-3">
        <p class="text-muted small mb-0" id="catalog-count" role="status"><?= count($produtos) ?> produto(s)</p>
        <button class="btn btn-sm btn-outline-primary" type="reset" form="filtros-catalogo" id="limpar-filtros" hidden>Limpar filtros</button>
    </div>
    <div id="cards-view" class="catalog-grid"<?php if ($podeEditar): ?>
        data-url-capa="<?= esc(url_to('restrito.produto.capa'), 'attr') ?>"
        data-token-nome="<?= esc(csrf_token(), 'attr') ?>"
        data-token-valor="<?= esc(csrf_hash(), 'attr') ?>"<?php endif ?>>
        <?php foreach ($produtos as $produto): ?>
            <?php
            [$classe, $rotulo] = $status($produto);
            $id = base64_encode($produto['id_produto']);
            $fotos = $fotosPorProduto[$produto['id_produto']] ?? [];
            $carrossel = 'fotos-produto-' . (int) $produto['id_produto'];
            $cor = '#' . ltrim(trim((string) $produto['cor_hex']), '#');
            $corValida = preg_match('/\A#(?:[a-f0-9]{3}|[a-f0-9]{6})\z/i', $cor);
            $nomeCompleto = $produto['nome'] . ($produto['cor_nome'] ? ' · ' . $produto['cor_nome'] : '');
            ?>
            <article class="card catalog-item" data-search="<?= esc($busca($produto), 'attr') ?>" data-produto="<?= (int) $produto['id_produto'] ?>"
                data-categoria="<?= (int) $produto['id_categoria'] ?>" data-cor="<?= $produto['cor_nome'] ? (int) $produto['id_cor'] : 0 ?>"
                data-situacao="<?= $classe ?: 'disponivel' ?>" data-saldo="<?= (int) $produto['saldoEstoque'] ?>">
                <?php if ($fotos): ?>
                    <div id="<?= $carrossel ?>" class="carousel slide catalog-carousel" data-bs-interval="false"
                         <?= count($fotos) > 1 ? 'data-galeria-arrastavel' : '' ?> role="region" aria-label="<?= esc('Fotos de ' . $nomeCompleto, 'attr') ?>">
                        <div class="carousel-inner">
                            <?php foreach ($fotos as $indice => $foto): ?>
                                <?php
                                $meta = (array) json_decode($foto['json'] ?? '{}', true);
                                $ajustes = \App\Libraries\GaleriaProduto::enquadramento($meta['enquadramento'] ?? null);
                                $posicao = $ajustes['x'] . '% ' . $ajustes['y'] . '%';
                                ?>
                                <div class="carousel-item<?= $indice === 0 ? ' active' : '' ?>" data-foto="<?= (int) $foto['id_produto_imagem'] ?>">
                                    <div class="catalog-image">
                                        <img src="<?= esc($foto['imagem'], 'attr') ?>" loading="lazy" draggable="false" alt="<?= esc('Foto ' . ($indice + 1) . ' de ' . $nomeCompleto, 'attr') ?>"
                                             style="object-position: <?= $posicao ?>; transform-origin: <?= $posicao ?>; transform: scale(<?= $ajustes['zoom'] / 100 ?>)">
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <?php if (count($fotos) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carrossel ?>" data-bs-slide="prev" aria-label="Foto anterior">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#<?= $carrossel ?>" data-bs-slide="next" aria-label="Próxima foto">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            </button>
                        <?php endif ?>
                    </div>
                    <?php if (count($fotos) > 1): ?>
                        <div class="catalog-photo-bar" data-capa-atual="<?= (int) $fotos[0]['id_produto_imagem'] ?>">
                            <span class="text-muted small" data-contador-fotos>1 de <?= count($fotos) ?></span>
                            <?php if ($podeEditar): ?>
                                <span class="stock-status" data-indicador-capa>Foto de capa</span>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-usar-capa hidden>Usar como capa</button>
                            <?php endif ?>
                            <small class="text-danger w-100" data-erro-capa role="status" hidden></small>
                        </div>
                    <?php endif ?>
                <?php else: ?>
                    <div class="catalog-image" aria-label="Produto sem foto"><i class="fa fa-cube" aria-hidden="true"></i></div>
                <?php endif ?>
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <h2><?= esc($produto['nome']) ?></h2>
                        <?php if ($podeEditar || $podeExcluir): ?>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm catalog-settings" data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="<?= esc('Opções de ' . $nomeCompleto, 'attr') ?>"><i class="fa fa-cog" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <?php if ($podeEditar): ?>
                                        <li><a class="dropdown-item" href="<?= url_to('restrito.produto.editar', $id) ?>">Editar produto</a></li>
                                    <?php endif ?>
                                    <?php if ($podeExcluir): ?>
                                        <li><button class="dropdown-item text-danger modalExcluir" type="button"
                                            data-id-excluir="<?= esc($produto['id_produto'], 'attr') ?>"
                                            data-url-excluir="<?= url_to('restrito.produto.excluir') ?>"
                                            data-mensagem-excluir="<?= esc('Confirma excluir ' . $nomeCompleto . '?', 'attr') ?>">Excluir produto</button></li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="catalog-code"><?= esc($produto['codigo']) ?> · <?= esc($produto['categoria']) ?></div>
                    <?php if ($produto['cor_nome']): ?>
                        <span class="catalog-color">
                            <?php if ($corValida): ?><span class="color-swatch" style="background-color: <?= esc($cor, 'attr') ?>" aria-hidden="true"></span><?php endif ?>
                            <?= esc($produto['cor_nome']) ?>
                        </span>
                    <?php endif ?>
                    <div class="catalog-balance">
                        <strong><?= esc($produto['saldoEstoque']) ?> un.</strong>
                        <span class="stock-status <?= $classe ?>"><?= $rotulo ?></span>
                    </div>
                </div>
                <?php if ($podeEntrada || $podeSaida): ?>
                    <div class="card-footer catalog-movements">
                        <?php if ($podeEntrada): ?><a class="btn btn-sm catalog-in" href="<?= url_to('restrito.entrada.formulario', $id) ?>"><i class="fa fa-arrow-down" aria-hidden="true"></i>Entrada</a><?php endif ?>
                        <?php if ($podeSaida): ?><a class="btn btn-sm catalog-out" href="<?= url_to('restrito.saida.formulario', $id) ?>"><i class="fa fa-arrow-up" aria-hidden="true"></i>Saída</a><?php endif ?>
                    </div>
                <?php endif ?>
            </article>
        <?php endforeach ?>
    </div>
    <p id="catalog-empty" class="empty-state mt-3" hidden>Nenhum produto corresponde aos filtros selecionados.</p>
<?php endif ?>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?php if (!empty($tb_produto)): ?>
<script src="/assets/js/estoque-catalogo.js"></script>

<?php endif ?>
<?= $this->endSection() ?>
