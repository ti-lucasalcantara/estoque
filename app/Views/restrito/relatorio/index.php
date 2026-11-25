<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Relatórios</h1>
        <p class="page-description">Acompanhe o saldo, a reposição e o giro dos produtos.</p>
    </div>
</header>
<div class="reports-grid">
    <?php foreach ($relatorios as $item): ?>
        <a class="card hub-card report-link" href="<?= url_to($item['rota']) ?>">
            <span class="hub-icon"><i class="fa fa-<?= esc($item['icone'], 'attr') ?>" aria-hidden="true"></i></span>
            <span>
                <strong class="d-block"><?= esc($item['titulo']) ?></strong>
                <small class="text-muted d-block mt-1"><?= esc($item['descricao']) ?></small>
            </span>
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>
