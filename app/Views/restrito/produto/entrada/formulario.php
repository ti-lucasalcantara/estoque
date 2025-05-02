<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>

<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Entrada de Produto</h4>
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

                <form method="POST" action="<?= url_to('restrito.entrada.salvar') ?>">
                    <input type="hidden" name="id_produto" value="<?=$produto['id_produto']?>">

                    <?php
                    if(isset($produto_entrada) && !empty($produto_entrada['id_produto_entrada'])){
                    ?>
                    <input type="hidden" name="id_produto_entrada" value="<?=base64_encode($produto_entrada['id_produto_entrada'] ?? '')?>">
                    <?php
                    }
                    ?>

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="data_entrada" class="form-label">*Data da Entrada</label>
                            <input type="date" class="form-control <?= empty(validation_show_error('data_entrada')) ? '' : 'is-invalid' ?>" id="data_entrada" name="data_entrada" value="<?= set_value('data_entrada', (isset($produto_entrada['data_entrada']) ? (date('Y-m-d',strtotime($produto_entrada['data_entrada']))) : '')    ) ?>" max="<?=date('Y-m-d')?>">
                            <small class="text-danger"><?= validation_show_error('data_entrada') ?></small>
                        </div>
                        <div class="col-md-2">
                            <label for="quantidade" class="form-label">*Quantidade</label>
                            <input type="number" class="form-control <?= empty(validation_show_error('quantidade')) ? '' : 'is-invalid' ?>" id="quantidade" name="quantidade" placeholder="Ex: 100" value="<?= set_value('quantidade', ($produto_entrada['quantidade'] ?? '')) ?>">
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
                                <option value="<?=$local['id_local']?>" <?= set_select('id_local', $local['id_local'], (isset($produto_entrada['id_local']) && $produto_entrada['id_local'] == $local['id_local'] ? true : false)) ?>><?=$local['local']?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <small class="text-danger"><?= validation_show_error('localizacao') ?></small>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="id_motivo_entrada" class="form-label">*Forma de Entrada</label>
                            <div class="input-group">
                                <select class="form-select <?= empty(validation_show_error('id_motivo_entrada')) ? '' : 'is-invalid' ?>" id="id_motivo_entrada" name="id_motivo_entrada">
                                    <option value="">Selecione</option>
                                    <?php
                                    if(isset($ref_motivo_entrada) && !empty($ref_motivo_entrada)){
                                        foreach ($ref_motivo_entrada as $motivo_entrada) {
                                    ?>
                                    <option value="<?=$motivo_entrada['id_motivo_entrada']?>" <?= set_select('id_motivo_entrada', $motivo_entrada['id_motivo_entrada'], (isset($produto_entrada['id_motivo_entrada']) && $produto_entrada['id_motivo_entrada'] == $motivo_entrada['id_motivo_entrada'] ? true : false)) ?>><?=$motivo_entrada['motivo_entrada']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <!-- Outras opções -->
                                </select>
                            </div>
                            <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('id_motivo_entrada') ?></small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" id="observacoes" rows="3" placeholder="Informações adicionais"><?= set_value('observacoes', ($produto_entrada['observacoes'] ?? '')) ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fe fe-check-circle me-2"></i> Registrar Entrada
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<?php
if(isset($entradas) && !empty($entradas)){
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
                            $x = sizeof($entradas);
                            $qtd_total = 0;
                            foreach ($entradas as $entrada) {
                                $qtd_total += $entrada['quantidade'];
                            ?>
                            <tr>
                                <td><?=$x?></td>
                                <td><?=dataPTBR($entrada['data_entrada'])?></td>
                                <td><span class="badge bg-success"><?=$entrada['motivo_entrada']?></span></td>
                                <td><?=$entrada['local']?><br><small><?=$entrada['usuarioCriacao']?></small></td>
                                <td><?=$entrada['quantidade']?></td>
                                <td><a href="<?=url_to('restrito.entrada.editar', base64_encode($entrada['id_produto']), base64_encode($entrada['id_produto_entrada']) )?>">editar</a></td>
                                <td><a href="javascript:void(0);" data-id-excluir="<?=$entrada['id_produto_entrada']?>" data-url-excluir="<?=url_to('restrito.entrada.excluir')?>" data-mensagem-excluir="Confirma excluir entrada [<?=$entrada['quantidade'];?>] do produto [<?=$produto['nome']?>] ?" class="modalExcluir">excluir</a></td>
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
