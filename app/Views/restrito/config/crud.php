<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>

<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary"><i class="fa fa-cogs"></i> <?=$titulo ?? ''?></h4>
    </div>
    <div class="page-rightheader">
        <a href="<?= url_to('restrito.config.index') ?>" class="btn btn-outline-primary">
            <i class="fe fe-arrow-left me-2"></i> Voltar
        </a>
    </div>
</div>
<!-- End Page header -->

<div class="card">
    <div class="card-body">
        <form action="<?=url_to('restrito.config.salvar')?>" method="post">
            <input type="hidden" name="titulo" value="<?= $titulo?>">
            <input type="hidden" name="tabela" value="<?= $tabela?>">

            <?php if (isset($item[getIdNameTabela($tabela)])): ?>
                <input type="hidden" name="id" value="<?= $item[getIdNameTabela($tabela)] ?>">
            <?php endif; ?>

            <?php foreach ($camposVisiveis as $campo): ?>
                <?php if ( $campo == getIdNameTabela($tabela) ) continue; ?>
                <div class="mb-3">
                    <label for="<?= $campo ?>" class="form-label"><?= ucfirst($campo) ?></label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="<?= $campo ?>" 
                        name="<?= $campo ?>" 
                        value="<?= $item[$campo] ?? '' ?>" 
                        placeholder="Digite <?= strtolower($campo) ?>"
                        required>
                </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fe fe-check me-2"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>



<?php
if(isset($dados) && !empty($dados) && isset($camposVisiveis) && !empty($camposVisiveis)){
?>
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="tabelaDados">
        <thead class="table-primary">
            <tr>
                <?php foreach ($camposVisiveis as $campo): ?>
                    <th><?= ucfirst($campo) ?></th>
                <?php endforeach; ?>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $linha): ?>
                <tr>
                    <?php foreach ($camposVisiveis as $campo): ?>
                        <td><?= esc($linha[$campo]) ?></td>
                    <?php endforeach; ?>
                    <td>
                        <a href="<?=url_to('restrito.config.edit', base64_encode($titulo), base64_encode($tabela), base64_encode($linha[getIdNameTabela($tabela)]) )?>" 
                           class="btn btn-sm btn-outline-primary me-1">
                            <i class="fe fe-edit"></i> Editar
                        </a>
                        <a href="javascript:void(0);" data-tabela-excluir="<?=$tabela?>" data-id-excluir="<?=$linha[getIdNameTabela($tabela)]?>" data-url-excluir="<?=url_to('restrito.config.excluir')?>" data-mensagem-excluir="Confirma excluir este registro ?" class="btn btn-sm btn-outline-danger modalExcluir" >
                            <i class="fe fe-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
}
?>
<?= $this->endSection() ?>


<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $('#tabelaDados').DataTable(); 
</script>
<?= $this->endSection() ?>