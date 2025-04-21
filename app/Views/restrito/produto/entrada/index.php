<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Produtos</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.entrada.formularioMultiplas')?>" class="btn btn-outline-primary">
                <i class="fe fe-plus me-2"></i>
                Cadastrar Entradas
            </a>
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
                            <th>DATA</th>
                            <th>PRODUTO</th>
                            <th>QTD</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>DATA</th>
                            <th>PRODUTO</th>
                            <th>QTD</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if(isset($tb_produto_entrada) && !empty($tb_produto_entrada)){
                            foreach ($tb_produto_entrada as $entrada) { 
                            ?>
                            <tr>
                                <td><?=dataPTBR($entrada['data_entrada']);?></td>
                                <td><?=$entrada['produto'];?><br><small><?=$entrada['categoria'];?></small></td>
                                <td><?=$entrada['quantidade'];?></td>
                                <td><a href="<?=url_to('restrito.entrada.editar', base64_encode($entrada['id_produto']), base64_encode($entrada['id_produto_entrada']) )?>">editar</a></td>
                                <td><a href="javascript:void(0);" data-id-excluir="<?=$entrada['id_produto_entrada']?>" data-url-excluir="<?=url_to('restrito.entrada.excluir')?>" data-mensagem-excluir="Confirma excluir entrada [<?=$entrada['quantidade'];?>] do produto [<?=$entrada['produto']?>] ?" class="modalExcluir">excluir</a></td>
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
