<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Relatório: Saldo Atual de Estoque</h4>
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
            <form id="formRelatorio" method="GET" action="<?=url_to('restrito.relatorio.saldoEstoque')?>">
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


<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-warehouse me-2"></i>Relatório de Estoque Atual</h5>
            </div>
            <div class="card-body table-responsive">
                <?php if (isset($produtos) && !empty($produtos)) : ?>
                <table id="tabela_estoque" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Estoque Atual</th>
                            <th>Estoque Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $index => $produto) :   ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <span style="display:inline-block;width:15px;height:15px;background-color:<?=getHexaCorProduto($produto)?>;margin-right:5px;border:1px solid #000;border-radius:3px;"></span>
                                    <strong><?= esc($produto['nome']) ?></strong>
                                    <small>(<?=getNomeCorProduto($produto)?>)</small>
                                    <br>
                                    <span class="badge border-secondary text-dark "><?= esc($produto['codigo']) ?></span>
                                </td>
                                <td>
                                    <span class="badge border-info col-10 text-dark"><?= esc($produto['categoria']) ?></span>
                                </td>
                                <td>
                                    <span class="badge text-dark fs-6 col-10 <?= $produto['saldoEstoque'] <= $produto['estoque_minimo'] ? 'border-danger' : 'border-success' ?>">
                                        <?= esc($produto['saldoEstoque']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge col-10 fs-6 border-warning text-dark"><?= esc($produto['estoque_minimo']) ?></span>
                                </td>
                               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else : ?>
                <p class="text-center text-muted mb-0">- Nenhum produto encontrado -</p>
                <?php endif; ?>
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
