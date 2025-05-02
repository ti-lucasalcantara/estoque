<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>

<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Saida de Produto</h4>
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
                    <h3 class="text-dark mb-1"><strong><?= esc($produto['nome']) ?></strong></h3>
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
                            <label for="data_saida" class="form-label">*Data da saida</label>
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
                            <label for="id_motivo_saida" class="form-label">*Forma de saida</label>
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
                                    <!-- Outras opções -->
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
                            <i class="fe fe-check-circle me-2"></i> Registrar saida
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<?php
if(isset($saidas) && !empty($saidas)){
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mb-5">
                    <h3 class="text-dark mb-1"><strong><?= esc($produto['nome']) ?></strong></h3>
                    <h6 class="text-muted"><?= esc($produto['codigo']) ?> | <?= esc($produto['categoria']) ?></h6>
               
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Local/Responsável</th>
                                <th>Quantidade</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $x = sizeof($saidas);
                            $qtd_total = 0;
                            foreach ($saidas as $saida) {
                                $qtd_total += $saida['quantidade'];
                            ?>
                            <tr>
                                <td><?=$x?></td>
                                <td><?=dataPTBR($saida['data_saida'])?></td>
                                <td><span class="badge bg-success"><?=$saida['motivo_saida']?></span></td>
                                <td><?=$saida['local']?><br><small><?=$saida['usuarioCriacao']?></small></td>
                                <td><?=$saida['quantidade']?></td>
                                <td><a href="<?=url_to('restrito.saida.editar', base64_encode($saida['id_produto']), base64_encode($saida['id_produto_saida']) )?>">editar</a></td>
                                <td><a href="javascript:void(0);" data-id-excluir="<?=$saida['id_produto_saida']?>" data-url-excluir="<?=url_to('restrito.saida.excluir')?>" data-mensagem-excluir="Confirma excluir saida [<?=$saida['quantidade'];?>] do produto [<?=$produto['nome']?>] ?" class="modalExcluir">excluir</a></td>
                            </tr>
                            <?php
                            $x--;
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="4" class="text-end"><strong>>> Total</strong></td>
                                <td><strong><?=$qtd_total?></strong></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
}
?>
<?= $this->endSection() ?>
