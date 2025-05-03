<?=$this->extend('restrito/template/principal')?> 

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-danger"><i class="fa fa-sign-out"></i> Saidas de Estoque</h4>
    </div>
    <div class="page-rightheader">
        <a href="<?=url_to('restrito.saida.formularioMultiplas')?>" class="btn btn-outline-primary">
            <i class="fe fe-plus me-2"></i> Cadastrar Saidas
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <form id="formFiltro" method="GET" action="<?=url_to('restrito.saida.index')?>">
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

<div class="mb-3">
    <input type="text" id="filtroGeral" class="form-control" placeholder="Busque por NOME DO PRODUTO, COR, CATEGORIA ou QUANTIDADE">
</div>

<div class="list-group">
    <?php 
    // Agrupa saidas por data
    $agrupadoPorData = [];
    if (isset($tb_produto_saida) && !empty($tb_produto_saida)) {
        foreach ($tb_produto_saida as $saida) {
            $agrupadoPorData[$saida['data_saida']][] = $saida;
        }
        
        foreach ($agrupadoPorData as $data => $saidas) {
        ?>
            <!-- Título da data -->
            <div class="list-group-item bg-danger text-white ">
                <strong><i class="fe fe-calendar me-1"></i> <?=dataPTBR($data);?></strong>
            </div>
            
            <?php 
            foreach ($saidas as $saida) {
                $saida['cor_nome'] = getNomeCorProduto($saida['TbProduto']);
                $saida['cor_hexa'] = getHexaCorProduto($saida['TbProduto']);
            ?>
                <div class="list-group-item list-group-item-action justify-content-between align-items-center saida-card" style="display: flex;"
                    data-produto="<?= strtolower($saida['produto']); ?>"
                    data-cor="<?= strtolower($saida['cor_nome']); ?>"
                    data-categoria="<?= strtolower($saida['categoria']); ?>"
                    data-quantidade="<?= strtolower($saida['quantidade']); ?>"
                    data-motivo="<?= strtolower($saida['motivo_saida']); ?>">

                    <div class="w-70">
                        <h6 class="mb-1 d-flex align-items-center">
                            <?php if (!empty($saida['cor_nome']) && !empty($saida['cor_hexa'])): ?>
                                <span style="display:inline-block; width:15px; height:15px; background-color:<?=$saida['cor_hexa']?>; border:1px solid #000; border-radius:3px; margin-right:5px;"></span>
                            <?php endif; ?>
                            <?=$saida['produto'];?>
                            <?php if (!empty($saida['cor_nome'])): ?>
                                (<?=$saida['cor_nome']?>)
                            <?php endif; ?>
                            <small class="text-muted ms-2"><?=$saida['categoria'];?></small>
                        </h6>
                        <span class="text-muted">
                            <i class="fe fe-box me-1"></i> <?=$saida['quantidade'];?> unid.
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <?=$saida['motivo_saida'] ?? ''?>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="<?=url_to('restrito.saida.editar', base64_encode($saida['id_produto']), base64_encode($saida['id_produto_saida']))?>" 
                           class="btn btn-sm btn-outline-primary me-2" title="Editar">
                            <i class="fe fe-edit"></i>
                        </a>
                        <a href="javascript:void(0);" 
                           data-id-excluir="<?=$saida['id_produto_saida']?>" 
                           data-url-excluir="<?=url_to('restrito.saida.excluir')?>" 
                           data-mensagem-excluir="Confirma excluir saida Qtd. [<?=$saida['quantidade'];?>] do produto [<?=$saida['produto']?>] ?" 
                           class="btn btn-sm btn-outline-danger modalExcluir" title="Excluir">
                            <i class="fe fe-trash"></i>
                        </a>
                    </div>
                </div>
            <?php } ?>
    <?php } 
    }else{
    ?>
    - Nenhuma saida registrada no período selecionado -
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
    $('#filtroGeral').on('input', function() {
        var filtro = $(this).val().toLowerCase().trim();

        $('.saida-card').each(function() {
            var card = $(this);
            var produto = card.data('produto') ? card.data('produto').toString().toLowerCase() : '';
            var cor = card.data('cor') ? card.data('cor').toString().toLowerCase() : '';
            var categoria = card.data('categoria') ? card.data('categoria').toString().toLowerCase() : '';
            var quantidade = card.data('quantidade') ? card.data('quantidade').toString() : '';
            var motivo = card.data('motivo') ? card.data('motivo').toString().toLowerCase() : '';

            var conteudo = produto + ' ' + cor + ' ' + categoria + ' ' + quantidade + ' ' + motivo;

            if (conteudo.includes(filtro)) {
                card.show();
            } else {
                card.hide();
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
