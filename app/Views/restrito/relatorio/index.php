<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Relatórios do Sistema</h4>
    </div>
</div>
<!-- End Page header -->

<!-- Row -->
<div class="row">
    <?php
    
    ?>

    <?php 
    if(isset($relatorios) && !empty($relatorios)):
        foreach ($relatorios as $rel):
            $url = '#';
            if( route_to($rel['rota']) ){
                $url = url_to($rel['rota']);
            }
    ?>
            <div class="col-md-6">
                <a href='<?=$url?>' class="card card-hover" style="cursor: pointer;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= esc($rel['titulo']) ?></h5>
                        <p class="text-muted">Clique para visualizar</p>
                    </div>
                </a>
            </div>
    <?php 
        endforeach;
    endif;
    ?>
</div>
<!-- End Row -->
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .card-hover:hover {
        background-color: #f0f0f0;
        transition: 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<?= $this->endSection() ?>
