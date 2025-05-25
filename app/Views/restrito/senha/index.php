<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary"><i class="fa fa-lock"></i> Alterar Senha</h4>
    </div>
</div>
<!-- End Page header -->

<!-- Row -->
<div class="row">
    <div class="card">
        <div class="card-body">
        <div class="col-md-6 offset-md-3">
            <form action="<?= url_to('restrito.salvar-nova-senha') ?>" method="post" novalidate>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control <?= empty(validation_show_error('nova_senha')) ? '' : 'is-invalid' ?>" id="nova_senha" name="nova_senha" value="<?=set_value('nova_senha')?>">
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" data-target="#nova_senha" style="cursor:pointer;">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                    <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('nova_senha') ?></small>
                </div>

                <div class="form-group">
                    <label for="confirma_senha">Confirmar Nova Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control <?= empty(validation_show_error('confirma_senha')) ? '' : 'is-invalid' ?>" id="confirma_senha" name="confirma_senha" value="<?=set_value('confirma_senha')?>">
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" data-target="#confirma_senha" style="cursor:pointer;">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                    <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('confirma_senha') ?></small>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-5">
                    <i class="fa fa-save"></i> Alterar Senha
                </button>
            </form>
        </div>
    </div>
    </div>
</div>
<!-- End Row -->
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Adicione aqui estilos personalizados se necessário -->
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    document.querySelectorAll('.toggle-password').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const input = document.querySelector(this.getAttribute('data-target'));
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
</script>
<?= $this->endSection() ?>
