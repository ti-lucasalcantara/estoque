<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Relatório: Produtos em Estoque Minimo</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.relatorio.index')?>" class="btn btn-outline-primary">
                <i class="fe fe-chevron-left me-2"></i>
                Voltar
            </a>
        </div>
    </div>
</div>
<!-- End Page header -->

<!-- Row -->
 
<!-- Filtros -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <form id="formRelatorio" method="GET" action="<?=url_to('restrito.relatorio.estoqueMinimo')?>">
                <div class="row g-3">
                    <?= $this->include('_componentes/form/ref_categoria_tb_produtos');?>
                    <!-- Botão -->
                    <div class="col-md-12 text-end">
                        <button type="submit" id="sendFormRelatorio" class="btn btn-primary mt-3">
                            <i class="fe fe-search me-2"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>

            </div>
        </div>
    </div>
</div>


<div class="card mt-4 <?=(!empty($produtosCriticos)) ? 'border-danger' : 'border-info'?>">
    <div class="card-header <?=(!empty($produtosCriticos)) ? 'bg-danger' : 'bg-info'?> text-white">
        <h5>⚠️ Produtos abaixo do estoque mínimo</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($produtosCriticos)) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Estoque Atual</th>
                            <th>Estoque Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;
                        foreach ($produtosCriticos as $p) :
                        ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?= esc($p['codigo']) ?></td>
                                <td>
                                    <span style="display:inline-block;width:15px;height:15px;background-color:<?=getHexaCorProduto($p)?>;margin-right:5px;border:1px solid #000;border-radius:3px;"></span>
                                    <?= esc($p['nome']) ?>
                                    <small class="text-muted">(<?=getNomeCorProduto($p)?>)</small>
                                </td>
                                <td><?= esc($p['categoria'] ?? '') ?></td>
                                <td class="text-danger"><strong><?= esc($p['saldoEstoque']) ?></strong></td>
                                <td><?= esc($p['estoque_minimo']) ?></td>
                            </tr>
                        <?php
                        $i++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="text-center text-muted mb-0">- Nenhum produto encontrado -</p>
        <?php endif; ?>
    </div>
</div>
<!-- End Row -->
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function () {
    $('#tabela_estoque').DataTable({
        pageLength: 25,
    });

    $("#formRelatorio").submit(function(){
        $("#sendFormRelatorio").loading();
    });
    
});
</script>
<?= $this->endSection() ?>
