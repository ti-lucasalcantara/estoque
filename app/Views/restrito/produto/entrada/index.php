<?=$this->extend('restrito/template/principal')?> 

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary"><i class="fa fa-sign-in"></i> Entradas de Estoque</h4>
    </div>
    <div class="page-rightheader">
        <a href="<?=url_to('restrito.entrada.formularioMultiplas')?>" class="btn btn-outline-primary">
            <i class="fe fe-plus me-2"></i> Cadastrar Entradas
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <form id="formFiltro" method="GET" action="<?=url_to('restrito.entrada.index')?>">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="data_inicio" class="form-label">Período</label>
                <input type="date" name="data_inicio" id="data_inicio" class="form-control"
                    value="<?= set_value('data_inicio', $data_inicio ?? '') ?>" max="<?=date('Y-m-d')?>">
            </div>
            <div class="col-md-3">
                <label for="data_fim" class="form-label">&nbsp;</label>
                <input type="date" name="data_fim" id="data_fim" class="form-control"
                    value="<?= set_value('data_fim', $data_fim ?? '') ?>" max="<?=date('Y-m-d')?>">
            </div>
            <div class="col-md-6 mt-5 text-end">
                <button type="submit" id="sendFormFiltro" class="btn btn-info mt-3">
                    <i class="fe fe-filter me-2"></i> Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="list-group">
    <?php 
    // Agrupa entradas por data
    $agrupadoPorData = [];
    if (isset($tb_produto_entrada) && !empty($tb_produto_entrada)) {
        foreach ($tb_produto_entrada as $entrada) {
            $agrupadoPorData[$entrada['data_entrada']][] = $entrada;
        }
        
        foreach ($agrupadoPorData as $data => $entradas) { ?>
            <!-- Título da data -->
            <div class="list-group-item bg-indigo ">
                <strong><i class="fe fe-calendar me-1"></i> <?=dataPTBR($data);?></strong>
            </div>
            
            <?php foreach ($entradas as $entrada) { ?>
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">
                            <?=$entrada['produto'];?>
                            <small class="text-muted">(<?=$entrada['categoria'];?>)</small>
                        </h6>
                        <span class="text-muted">
                            <i class="fe fe-box me-1"></i> <?=$entrada['quantidade'];?> unid.
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="<?=url_to('restrito.entrada.editar', base64_encode($entrada['id_produto']), base64_encode($entrada['id_produto_entrada']))?>" 
                           class="btn btn-sm btn-outline-primary me-2" title="Editar">
                            <i class="fe fe-edit"></i>
                        </a>
                        <a href="javascript:void(0);" 
                           data-id-excluir="<?=$entrada['id_produto_entrada']?>" 
                           data-url-excluir="<?=url_to('restrito.entrada.excluir')?>" 
                           data-mensagem-excluir="Confirma excluir entrada Qtd. [<?=$entrada['quantidade'];?>] do produto [<?=$entrada['produto']?>] ?" 
                           class="btn btn-sm btn-outline-danger modalExcluir" title="Excluir">
                            <i class="fe fe-trash"></i>
                        </a>
                    </div>
                </div>
            <?php } ?>
    <?php } 
    }else{
    ?>

    - Nenhuma entrada registrada no período selecionado -
    <?php
    } 
    ?>

</div>



<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.6em;
        border-radius: 0.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    $('#filtroData, #filtroProduto').on('input change', function() {
        var dataFiltro = $('#filtroData').val();
        var produtoFiltro = $('#filtroProduto').val().toLowerCase();

        $('.entrada-card').each(function() {
            var card = $(this);
            var dataCard = card.data('data');
            var produtoCard = card.data('produto').toLowerCase();

            var matchData = !dataFiltro || dataCard.includes(dataFiltro);
            var matchProduto = !produtoFiltro || produtoCard.includes(produtoFiltro);

            card.toggle(matchData && matchProduto);
        });
    });

    $('#btnLimparFiltros').click(function() {
        $('#filtroData').val('');
        $('#filtroProduto').val('');
        $('.entrada-card').show();
    });
});
</script>
<?= $this->endSection() ?>
