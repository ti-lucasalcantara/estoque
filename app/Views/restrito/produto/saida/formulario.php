<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>

<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-danger">Saida de Produto</h4>
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
                    <h3 class="text-dark mb-1">
                        <?php if (!empty($produto['cor_nome']) && !empty($produto['cor_hexa'])): ?>
                            <span style="display:inline-block; width:15px; height:15px; background-color:<?=$produto['cor_hexa']?>; border:1px solid #000; border-radius:3px; margin-right:5px;"></span>
                        <?php endif; ?>
                        <?=$produto['nome'];?>
                        <?php if (!empty($produto['cor_nome'])): ?>
                            (<?=$produto['cor_nome']?>)
                        <?php endif; ?>
                    </h3>
                    <h6 class="text-muted"><?= esc($produto['codigo']) ?> | <?= esc($produto['categoria']) ?></h6>
                </div>

                <form method="POST" action="<?= url_to('restrito.saida.salvar') ?>">
                    <input type="hidden" name="id_produto" value="<?=$produto['id_produto']?>">

                    <?php
                    if(isset($produto_saida) && !empty($produto_saida['id_produto_saida'])){
                    ?>
                    <input type="hidden" name="id_produto_saida" value="<?=base64_encode($produto_saida['id_produto_saida'] ?? '')?>">
                    <?php
                    }
                    ?>

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="data_saida" class="form-label">*Data da Saida</label>
                            <input type="date" class="form-control <?= empty(validation_show_error('data_saida')) ? '' : 'is-invalid' ?>" id="data_saida" name="data_saida" value="<?= set_value('data_saida', (isset($produto_saida['data_saida']) ? (date('Y-m-d',strtotime($produto_saida['data_saida']))) : '')    ) ?>" max="<?=date('Y-m-d')?>">
                            <small class="text-danger"><?= validation_show_error('data_saida') ?></small>
                        </div>
                        <div class="col-md-2">
                            <label for="quantidade" class="form-label">*Quantidade</label>
                            <input type="number" class="form-control <?= empty(validation_show_error('quantidade')) ? '' : 'is-invalid' ?>" id="quantidade" name="quantidade" placeholder="Ex: 100" value="<?= set_value('quantidade', ($produto_saida['quantidade'] ?? '')) ?>">
                            <small class="text-danger"><?= validation_show_error('quantidade') ?></small>
                        </div>

                        <div class="col-md-4">
                            <label for="id_local" class="form-label">*Localização</label>
                            <select class="form-select <?= empty(validation_show_error('id_local')) ? '' : 'is-invalid' ?>" id="id_local" name="id_local">
                                <option value="">Selecione</option>
                                <?php
                                if(isset($ref_local) && !empty($ref_local)){
                                    foreach ($ref_local as $local) {
                                ?>
                                <option value="<?=$local['id_local']?>" <?= set_select('id_local', $local['id_local'], (isset($produto_saida['id_local']) && $produto_saida['id_local'] == $local['id_local'] ? true : false)) ?>><?=$local['local']?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <small class="text-danger"><?= validation_show_error('localizacao') ?></small>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="id_motivo_saida" class="form-label">*Forma de Saida</label>
                            <div class="input-group">
                                <select class="form-select <?= empty(validation_show_error('id_motivo_saida')) ? '' : 'is-invalid' ?>" id="id_motivo_saida" name="id_motivo_saida">
                                    <option value="">Selecione</option>
                                    <?php
                                    if(isset($ref_motivo_saida) && !empty($ref_motivo_saida)){
                                        foreach ($ref_motivo_saida as $motivo_saida) {
                                    ?>
                                    <option value="<?=$motivo_saida['id_motivo_saida']?>" <?= set_select('id_motivo_saida', $motivo_saida['id_motivo_saida'], (isset($produto_saida['id_motivo_saida']) && $produto_saida['id_motivo_saida'] == $motivo_saida['id_motivo_saida'] ? true : false)) ?>><?=$motivo_saida['motivo_saida']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('id_motivo_saida') ?></small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" id="observacoes" rows="3" placeholder="Informações adicionais"><?= set_value('observacoes', ($produto_saida['observacoes'] ?? '')) ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fe fe-check-circle me-2"></i> Registrar Saida
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<?php if (isset($saidas) && !empty($saidas)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <h3 class="text-dark mb-1">
                    <?php if (!empty($produto['cor_nome']) && !empty($produto['cor_hexa'])): ?>
                        <span style="display:inline-block; width:15px; height:15px; background-color:<?=$produto['cor_hexa']?>; border:1px solid #000; border-radius:3px; margin-right:5px;"></span>
                    <?php endif; ?>
                    <?=$produto['nome'];?>
                    <?php if (!empty($produto['cor_nome'])): ?>
                        (<?=$produto['cor_nome']?>)
                    <?php endif; ?>
                </h3>
                <h6 class="text-muted"><?= esc($produto['codigo']) ?> | <?= esc($produto['categoria']) ?></h6>

                <!--  
                <div class="mb-3">
                    <input type="text" id="filtroGeral" class="form-control" placeholder="Buscar por motivo, quantidade, local ou responsável...">
                </div>
                -->
                <div class="list-group">
                    <?php
                    $agrupadoPorData = [];
                    $qtd_total = 0;
                    foreach ($saidas as $saida) {
                        $agrupadoPorData[$saida['data_saida']][] = $saida;
                        $qtd_total += $saida['quantidade'];
                    }

                    foreach ($agrupadoPorData as $data => $listaSaidas):
                    ?>
                        <!-- Título da data -->
                        <div class="list-group-item bg-danger text-white">
                            <strong><i class="fe fe-calendar me-1"></i> <?= dataPTBR($data); ?></strong>
                        </div>

                        <?php
                        $index = count($listaSaidas);
                        foreach ($listaSaidas as $saida):
                        ?>
                        <div class="list-group-item list-group-item-action justify-content-between align-items-center saida-card" style="display: flex;"
                            data-motivo="<?= strtolower($saida['motivo_saida']); ?>"
                            data-quantidade="<?= strtolower($saida['quantidade']); ?>"
                            data-local="<?= strtolower($saida['local']); ?>"
                            data-responsavel="<?= strtolower($saida['usuarioCriacao']); ?>">

                            <div class="w-70">
                                <h6 class="mb-1 d-flex align-items-center">
                                    #<?= $index-- ?>
                                    <span class="badge bg-success ms-2"><?= $saida['motivo_saida'] ?></span>
                                    <small class="text-muted ms-2"><?= $saida['local'] ?> / <?= $saida['usuarioCriacao'] ?></small>
                                </h6>
                                <span class="text-muted">
                                    <i class="fe fe-box me-1"></i> <?= $saida['quantidade'] ?> unid.
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="<?= url_to('restrito.saida.editar', base64_encode($saida['id_produto']), base64_encode($saida['id_produto_saida'])) ?>"
                                   class="btn btn-sm btn-outline-primary me-2" title="Editar">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <a href="javascript:void(0);"
                                   data-id-excluir="<?= $saida['id_produto_saida'] ?>"
                                   data-url-excluir="<?= url_to('restrito.saida.excluir') ?>"
                                   data-mensagem-excluir="Confirma excluir saida [<?= $saida['quantidade'] ?>] do produto [<?= $produto['nome'] ?>] ?"
                                   class="btn btn-sm btn-outline-danger modalExcluir" title="Excluir">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <!-- Total geral -->
                    <div class="list-group-item bg-light d-flex justify-content-between">
                        <div><strong>Total de saidas</strong></div>
                        <div><strong><?= $qtd_total ?> unid.</strong></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>



<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    $('#filtroGeral').on('input', function() {
        var filtro = $(this).val().toLowerCase().trim();

        $('.saida-card').each(function() {
            var card = $(this);
            var motivo = card.data('motivo') ? card.data('motivo').toString().toLowerCase() : '';
            var quantidade = card.data('quantidade') ? card.data('quantidade').toString() : '';
            var local = card.data('local') ? card.data('local').toString().toLowerCase() : '';
            var responsavel = card.data('responsavel') ? card.data('responsavel').toString().toLowerCase() : '';

            var conteudo = motivo + ' ' + quantidade + ' ' + local + ' ' + responsavel;

            if (conteudo.includes(filtro)) {
                card.css('display', '');
            } else {
                card.css('display', 'none');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
