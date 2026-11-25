<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<header class="page-header">
    <div>
        <h1 class="page-title mb-0">Alterar senha</h1>
        <p class="page-description">Escolha uma nova senha para sua conta.</p>
    </div>
</header>
<section class="card account-form">
    <div class="card-body">
        <form action="<?= url_to('restrito.salvar-nova-senha') ?>" method="post">
            <?php foreach (['nova_senha' => 'Nova senha', 'confirma_senha' => 'Confirmar nova senha'] as $campo => $rotulo): ?>
                <?php $erro = validation_show_error($campo); ?>
                <div class="mb-3">
                    <label class="form-label" for="<?= $campo ?>"><?= $rotulo ?></label>
                    <div class="input-group">
                        <input type="password" id="<?= $campo ?>" name="<?= $campo ?>" class="form-control <?= $erro ? 'is-invalid' : '' ?>" required autocomplete="new-password" aria-describedby="erro-<?= $campo ?>">
                        <button type="button" class="btn btn-outline-primary" data-password="<?= $campo ?>" aria-pressed="false">Mostrar</button>
                    </div>
                    <small id="erro-<?= $campo ?>" class="text-danger"><?= $erro ?></small>
                </div>
            <?php endforeach ?>
            <button type="submit" class="btn btn-primary">Salvar nova senha</button>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
    document.querySelectorAll('[data-password]').forEach(botao => {
        botao.addEventListener('click', () => {
            const campo = document.getElementById(botao.dataset.password);
            const visivel = campo.type === 'password';
            campo.type = visivel ? 'text' : 'password';
            botao.textContent = visivel ? 'Ocultar' : 'Mostrar';
            botao.setAttribute('aria-pressed', String(visivel));
        });
    });
</script>
<?= $this->endSection() ?>