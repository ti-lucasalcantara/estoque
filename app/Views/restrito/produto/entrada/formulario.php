<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>

<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Entrada de Produto</h4>
    </div>
    <div class="page-rightheader">
        <a href="<?= url_to('restrito.produto.index') ?>" class="btn btn-outline-primary">
            <i class="fe fe-arrow-left me-2"></i> Voltar
        </a>
    </div>
</div>
<!-- End Page header -->

<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <!-- Destaque do Produto -->
                <div class="mb-5 pb-3 border-bottom">
                    <h3 class="text-dark mb-1"><strong><?= esc($produto['nome']) ?></strong></h3>
                    <h6 class="text-muted">Código: <?= esc($produto['codigo']) ?> | <?= esc($produto['categoria']) ?></h6>
                </div>

                <form method="POST" action="<?= url_to('restrito.entrada.salvar') ?>">
                    <input type="hidden" name="id_produto" value="<?=$produto['id_produto']?>">

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="data_entrada" class="form-label">*Data da Entrada</label>
                            <input type="date" class="form-control <?= empty(validation_show_error('data_entrada')) ? '' : 'is-invalid' ?>" id="data_entrada" name="data_entrada" value="<?= set_value('data_entrada') ?>" max="<?=date('Y-m-d')?>">
                            <small class="text-danger"><?= validation_show_error('data_entrada') ?></small>
                        </div>
                        <div class="col-md-2">
                            <label for="quantidade" class="form-label">*Quantidade</label>
                            <input type="number" class="form-control <?= empty(validation_show_error('quantidade')) ? '' : 'is-invalid' ?>" id="quantidade" name="quantidade" placeholder="Ex: 100" value="<?= set_value('quantidade') ?>">
                            <small class="text-danger"><?= validation_show_error('quantidade') ?></small>
                        </div>

                        <div class="col-md-6">
                            <label for="localizacao" class="form-label">*Localização</label>
                            <select class="form-select <?= empty(validation_show_error('id_categoria')) ? '' : 'is-invalid' ?>" id="id_categoria" name="id_categoria">
                                <option value="">Selecione</option>
                                <option value="Loja" <?= set_select('localizacao', 'Loja') ?>>Loja</option>
                                <option value="Depósito 1" <?= set_select('localizacao', 'Depósito 1') ?>>Depósito 1</option>
                                <option value="Depósito 2" <?= set_select('localizacao', 'Depósito 2') ?>>Depósito 2</option>
                            </select>
                            <small class="text-danger"><?= validation_show_error('localizacao') ?></small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" id="observacoes" rows="3" placeholder="Informações adicionais"><?= set_value('observacoes') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fe fe-check-circle me-2"></i> Registrar Entrada
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<?= $this->endSection() ?>
