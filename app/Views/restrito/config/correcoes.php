<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Regras de correção</h1>
        <p class="page-description">Controle quem pode alterar ou excluir uma entrada ou saída depois de registrada.</p>
    </div>
    <a href="<?= url_to('restrito.config.index') ?>" class="btn btn-outline-primary">Voltar às configurações</a>
</header>
<?php if ($somenteAdministrador === null): ?>
    <p class="form-guidance"><i class="fa fa-info-circle" aria-hidden="true"></i><span>A regra padrão permite correções a todos os usuários ativos. Peça ao responsável pelo sistema para aplicar a atualização do banco e liberar a edição desta regra.</span></p>
<?php else: ?>
    <form method="post" action="<?= url_to('restrito.config.salvarCorrecoes') ?>" class="card w-100">
        <?= csrf_field() ?>
        <div class="card-body">
            <p id="explicacao-correcao" class="form-guidance">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <span>Por exemplo, corrigir a quantidade de um sofá em uma saída já registrada. A regra vale apenas para correções. O lançamento de novas entradas e saídas continua disponível para a equipe.</span>
            </p>
            <fieldset aria-describedby="explicacao-correcao">
                <legend class="card-title">Quem pode corrigir movimentações?</legend>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="somente_administrador" id="correcao-equipe" value="0" <?= !$somenteAdministrador ? 'checked' : '' ?>>
                    <label class="form-check-label" for="correcao-equipe">Todos os usuários ativos</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="somente_administrador" id="correcao-admin" value="1" <?= $somenteAdministrador ? 'checked' : '' ?>>
                    <label class="form-check-label" for="correcao-admin">Somente administradores</label>
                </div>
            </fieldset>
            <p class="text-muted small mb-0 mt-3">Regra salva: <?= $somenteAdministrador ? 'Somente administradores' : 'Todos os usuários ativos' ?>.</p>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Salvar regra</button>
        </div>
    </form>
<?php endif ?>
<?= $this->endSection() ?>
