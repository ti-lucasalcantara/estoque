<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Produtos</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.produto.formulario')?>" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>
                Cadastrar Produto
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
                <table id="tabela_produtos" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>CÓDIGO</th>
                            <th>PRODUTO</th>
                            <th>CATEGORIA</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>CÓDIGO</th>
                            <th>PRODUTO</th>
                            <th>CATEGORIA</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if(isset($tb_produto) && !empty($tb_produto)){
                            foreach ($tb_produto as $produto) {
                        ?>
                        <tr>
                            <td><?=$produto['codigo']?></td>
                            <td><?=$produto['nome']?></td>
                            <td><?=$produto['categoria']?></td>
                            <td><a href="<?=url_to('restrito.produto.editar', base64_encode($produto['id_produto']))?>">editar</a></td>
                            <td><a href="javascript:void(0);" data-id-excluir="<?=$produto['id_produto']?>" data-url-excluir="<?=url_to('restrito.produto.excluir')?>" data-mensagem-excluir="Confirma excluir o produto [<?=$produto['nome']?>] ?" class="modalExcluir">excluir</a></td>
                            <td><a href="<?=url_to('restrito.entrada.formulario', base64_encode($produto['id_produto']))?>">entrada</a></td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
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
$('#tabela_produtos').DataTable({
    "scrollY": "800px", // Define a altura máxima com scroll
    "scrollCollapse": true, // Mantém a tabela ajustada ao conteúdo
    "paging": false, // Desativa a paginação
    "ordering": false, // Ativa a ordenação
    "info": false, // Oculta informações de registros (opcional)
}); 
</script>
<?= $this->endSection() ?>
