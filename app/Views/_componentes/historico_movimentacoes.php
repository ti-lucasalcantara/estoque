<?php
use App\Libraries\PermissoesEstoque;
use App\Libraries\PoliticaCorrecoes;

$acao = $tipo === 'entrada' ? 'entrada' : 'saída';
$rota = 'restrito.' . $tipo;
$hoje = date('Y-m-d');
$inicioMes = date('Y-m-01');
$podeCorrigir = $registros && (PermissoesEstoque::tem($tipo . '.editar') || PermissoesEstoque::tem($tipo . '.excluir'))
    && (new PoliticaCorrecoes())->permiteCorrigir();
$podeEditar = $podeCorrigir && PermissoesEstoque::tem($tipo . '.editar');
$podeExcluir = $podeCorrigir && PermissoesEstoque::tem($tipo . '.excluir');
$grupos = [];
foreach ($registros as $registro) {
    $grupos[substr($registro['data_' . $tipo], 0, 10)][] = $registro;
}
?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Histórico de <?= $acao ?>s</h1>
        <p class="page-description">Consulte as movimentações por período e encontre lançamentos pela busca.</p>
    </div>
    <?php if (PermissoesEstoque::tem($tipo . '.criar')): ?>
        <a href="<?= url_to($rota . '.formularioMultiplas') ?>" class="btn btn-primary"><i class="fa fa-plus me-2" aria-hidden="true"></i>Registrar <?= $acao ?></a>
    <?php endif ?>
</header>

<section class="filter-panel" aria-labelledby="titulo-filtros-historico">
    <h2 class="card-title mb-3" id="titulo-filtros-historico">Filtros do histórico</h2>
    <?php if (!$periodoValido): ?>
        <p class="form-guidance"><i class="fa fa-info-circle" aria-hidden="true"></i><span>O período informado é inválido. Exibindo os lançamentos deste mês.</span></p>
    <?php endif ?>
    <form id="periodo-historico" method="get" action="<?= url_to($rota . '.index') ?>" data-tipo="<?= $tipo ?>"
          data-inicio-padrao="<?= $inicioMes ?>" data-fim-padrao="<?= $hoje ?>">
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="data_inicio">Data inicial</label>
                <input class="form-control" id="data_inicio" name="data_inicio" type="date" value="<?= esc($data_inicio, 'attr') ?>" max="<?= $hoje ?>" required>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="data_fim">Data final</label>
                <input class="form-control" id="data_fim" name="data_fim" type="date" value="<?= esc($data_fim, 'attr') ?>" max="<?= $hoje ?>" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="fa fa-filter me-2" aria-hidden="true"></i>Aplicar período</button>
            </div>
            <div class="col-auto">
                <a href="<?= url_to($rota . '.index') ?>" class="btn btn-outline-primary" id="limpar-historico">Limpar filtros</a>
            </div>
        </div>
        <p class="text-danger small mt-2 mb-0" id="erro-periodo" role="status" hidden>A data final deve ser igual ou posterior à data inicial.</p>
    </form>
    <div class="mt-3">
        <label class="form-label" for="buscar-historico">Buscar no período selecionado</label>
        <input type="search" id="buscar-historico" class="form-control" placeholder="Produto, código, cor, motivo ou local">
    </div>
</section>

<div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
    <p class="text-muted mb-0 small"><?= esc(dataPTBR($data_inicio)) ?> a <?= esc(dataPTBR($data_fim)) ?></p>
    <p id="totais-historico" class="text-muted mb-0 small" role="status"></p>
</div>
<p class="empty-state" id="historico-vazio"<?= $registros ? ' hidden' : '' ?>>Nenhuma <?= $acao ?> registrada no período selecionado.</p>

<?php foreach ($grupos as $dia => $lista): ?>
    <section class="card" data-dia-historico>
        <div class="card-header"><h2 class="card-title"><i class="fa fa-calendar me-2" aria-hidden="true"></i><time datetime="<?= esc($dia, 'attr') ?>"><?= esc(dataPTBR($dia)) ?></time></h2></div>
        <ul class="list-group list-group-flush">
            <?php foreach ($lista as $registro): ?>
                <?php
                $motivo = $registro['motivo_' . $tipo] ?? 'Motivo não informado';
                $local = $registro['local'] ?? 'Local não informado';
                $cor = trim((string) ($registro['cor_hex'] ?? ''));
                $corValida = preg_match('/\A#(?:[0-9a-f]{3}|[0-9a-f]{6})\z/i', $cor);
                $id = (int) $registro['id_produto_' . $tipo];
                $quantidade = (int) $registro['quantidade'];
                $termos = implode(' ', [$registro['produto'], $registro['codigo'], $registro['categoria'], $registro['cor_nome'], $quantidade, $motivo, $local]);
                ?>
                <li class="list-group-item movement-record" data-busca-historico="<?= esc($termos, 'attr') ?>" data-quantidade="<?= $quantidade ?>">
                    <div class="movement-record-info">
                        <strong><?= esc($registro['produto']) ?></strong>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <small class="text-muted"><?= esc($registro['codigo']) ?> · <?= esc($registro['categoria']) ?></small>
                            <?php if ($registro['cor_nome']): ?>
                                <span class="catalog-color mt-0"><?php if ($corValida): ?><span class="color-swatch" style="background-color: <?= esc($cor, 'attr') ?>" aria-hidden="true"></span><?php endif ?><?= esc($registro['cor_nome']) ?></span>
                            <?php endif ?>
                        </div>
                        <small class="text-muted d-block mt-1"><?= esc($motivo) ?> · <?= esc($local) ?></small>
                    </div>
                    <strong class="movement-record-quantity <?= $tipo === 'entrada' ? 'text-success' : 'text-danger' ?>">
                        <?= $tipo === 'entrada' ? '+' : '−' ?><?= number_format($quantidade, 0, ',', '.') ?> un.
                    </strong>
                    <?php if ($podeEditar || $podeExcluir): ?>
                        <div class="page-actions">
                            <?php if ($podeEditar): ?><a class="btn btn-sm btn-outline-primary" href="<?= url_to($rota . '.editar', base64_encode((string) $registro['id_produto']), base64_encode((string) $id)) ?>">Editar</a><?php endif ?>
                            <?php if ($podeExcluir): ?>
                                <button class="btn btn-sm btn-outline-danger modalExcluir" type="button"
                                    data-id-excluir="<?= $id ?>" data-url-excluir="<?= url_to($rota . '.excluir') ?>"
                                    data-mensagem-excluir="<?= esc('Confirma excluir a ' . $acao . ' de ' . $registro['produto'] . '?', 'attr') ?>">Excluir</button>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </section>
<?php endforeach ?>
