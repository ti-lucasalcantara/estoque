<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Novo Produto</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.produto.index')?>" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>
                Voltar
            </a>

            <a href="javascript:void(0);"  class="btn btn-primary btn-pill" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-calendar me-2 fs-14"></i> Search By Date</a>
            <div class="dropdown-menu border-0">
                    <a class="dropdown-item" href="javascript:void(0);">Today</a>
                    <a class="dropdown-item" href="javascript:void(0);">Yesterday</a>
                    <a class="dropdown-item active" href="javascript:void(0);">Last 7 days</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last 30 days</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last 6 months</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last year</a>
            </div>
        </div>
    </div>
</div>
<!--End Page header-->
<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="formCadastroProduto" method="POST" action="<?=url_to('restrito.produto.salvar')?>" novalidate>
                    <?php
                    if(isset($produto) && !empty($produto['id_produto'])){
                    ?>
                    <input type="hidden" name="id_produto" value="<?=base64_encode($produto['id_produto'] ?? '')?>">
                    <?php
                    }
                    ?>
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="codigo" class="form-label">*Código</label>
                            <input type="text" class="form-control <?= empty(validation_show_error('codigo')) ? '' : 'is-invalid' ?>" id="codigo" name="codigo" placeholder="Código" value="<?= set_value('codigo', ($produto['codigo'] ?? '') ) ?>">
                            <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('codigo') ?></small>
                        </div>
                        <div class="col-md-6">
                            <label for="nome" class="form-label">*Nome do Produto</label>
                            <input type="text" class="form-control <?= empty(validation_show_error('nome')) ? '' : 'is-invalid' ?>" id="nome" name="nome" placeholder="Digite o nome do produto" value="<?= set_value('nome', ($produto['nome'] ?? '')) ?>">
                            <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('nome') ?></small>
                        </div>
                        <div class="col-md-4">
                            <label for="id_categoria" class="form-label">*Categoria</label>
                            <div class="input-group">
                                <select class="form-select <?= empty(validation_show_error('id_categoria')) ? '' : 'is-invalid' ?>" id="id_categoria" name="id_categoria">
                                    <option value="">Selecione</option>
                                    <?php
                                    if(isset($ref_categoria) && !empty($ref_categoria)){
                                        foreach ($ref_categoria as $categoria) {
                                    ?>
                                    <option value="<?=$categoria['id_categoria']?>" <?= set_select('id_categoria', $categoria['id_categoria'], (isset($produto['id_categoria']) && $produto['id_categoria'] == $categoria['id_categoria'] ? true : false) )?>><?=$categoria['categoria']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <!-- Outras opções -->
                                </select>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                                    <i class="fa fa-cog"></i>
                                </button>
                            </div>
                            <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('id_categoria') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea oninput="atualizarContador()" maxlength="500" class="form-control <?= empty(validation_show_error('descricao')) ? '' : 'is-invalid' ?>" id="descricao" name="descricao" rows="6" placeholder="Descrição do produto"><?=set_value('descricao', ($produto['descricao'] ?? ''))?></textarea>
                            <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('descricao') ?></small>
                            <small id="contadorDescricao" class="text-muted">Restam 500 caracteres</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>

function atualizarContador() {
    const textarea = document.getElementById('descricao');
    const contador = document.getElementById('contadorDescricao');
    const maxCaracteres = 500;
    const caracteresRestantes = maxCaracteres - textarea.value.length;
    if( caracteresRestantes >= 0){
        contador.textContent = `Restam ${caracteresRestantes} caracteres`;
    }else{
        contador.textContent = `Texto muito longo. O limite é de 500 caracteres.`;
    }
}

document.addEventListener('DOMContentLoaded', atualizarContador);
</script>
<?= $this->endSection() ?>
