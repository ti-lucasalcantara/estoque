<header class="page-header">
    <div>
        <h1 class="page-title mb-0"><?= esc($titulo) ?></h1>
        <p class="page-description"><?= esc($descricao) ?></p>
    </div>
    <a class="btn btn-outline-primary" href="<?= url_to('restrito.relatorio.index') ?>"><i class="fa fa-arrow-left me-2" aria-hidden="true"></i>Voltar aos relatórios</a>
</header>

<section class="card" aria-labelledby="titulo-filtros-relatorio">
    <div class="card-header"><h2 class="card-title" id="titulo-filtros-relatorio">Filtros do relatório</h2></div>
    <form method="get" action="<?= url_to($rotaConsulta) ?>" id="form-relatorio">
        <div class="card-body">
            <p class="text-muted small mb-3">Deixe categorias e produtos em branco para consultar todos.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="ref_categoria">Categorias</label>
                    <select name="ref_categoria[]" id="ref_categoria" class="form-select" multiple>
                        <?php foreach ($categoriasDisponiveis as $categoria): ?>
                            <?php $id = (int) $categoria['id_categoria']; ?>
                            <option value="<?= $id ?>"<?= in_array($id, $filtros['categorias'], true) ? ' selected' : '' ?>><?= esc($categoria['categoria']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="tb_produtos">Produtos</label>
                    <select name="tb_produtos[]" id="tb_produtos" class="form-select" multiple>
                        <?php foreach ($produtosDisponiveis as $opcao): ?>
                            <?php $id = (int) $opcao['id_produto']; ?>
                            <option value="<?= $id ?>" data-categoria="<?= (int) $opcao['id_categoria'] ?>"
                                data-cor="<?= esc($opcao['cor_hex'] ?? '', 'attr') ?>"<?= in_array($id, $filtros['produtos'], true) ? ' selected' : '' ?>>
                                <?= esc($opcao['nome'] . ' · ' . $opcao['codigo'] . ($opcao['cor_nome'] ? ' · ' . $opcao['cor_nome'] : '')) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <?php if ($comPeriodo): ?>
                    <div class="col-md-4">
                        <label class="form-label" for="tipo_periodo">Período</label>
                        <select id="tipo_periodo" name="tipo_periodo" class="form-select">
                            <option value="especifico"<?= !$todo ? ' selected' : '' ?>>Intervalo de datas</option>
                            <option value="todo"<?= $todo ? ' selected' : '' ?>>Todo o histórico</option>
                        </select>
                    </div>
                    <?php foreach (['data_inicio' => ['Data inicial', $inicio, date('Y-m-01')], 'data_fim' => ['Data final', $fim, date('Y-m-d')]] as $campo => [$rotulo, $valor, $padrao]): ?>
                        <div class="col-sm-6 col-md-4" data-datas-relatorio<?= $todo ? ' hidden' : '' ?>>
                            <label class="form-label" for="<?= $campo ?>"><?= $rotulo ?></label>
                            <input id="<?= $campo ?>" name="<?= $campo ?>" type="date" class="form-control" max="<?= date('Y-m-d') ?>"
                                value="<?= esc(is_string($valor) ? $valor : $padrao, 'attr') ?>"<?= $todo ? ' disabled' : ' required' ?>>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
            <?php if (!$periodoValido): ?>
                <p class="text-danger small mb-0 mt-3" role="alert">Informe duas datas válidas, com a data final igual ou posterior à inicial e até hoje.</p>
            <?php endif ?>
        </div>
        <div class="card-footer d-flex flex-wrap justify-content-end gap-2">
            <a href="<?= url_to($rotaConsulta) ?>" class="btn btn-outline-primary">Limpar filtros</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-filter me-2" aria-hidden="true"></i>Aplicar filtros</button>
        </div>
    </form>
</section>
