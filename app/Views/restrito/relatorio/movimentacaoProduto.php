<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Relatório: Movimentação de Produtos</h4>
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

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <form id="formRelatorio" method="GET" action="<?=url_to('restrito.relatorio.movimentacaoProduto')?>">
                <div class="row g-3">

                    <?= $this->include('_componentes/form/ref_categoria_tb_produtos');?>

                    <!-- Tipo de período -->
                    <div class="col-md-3">
                        <label for="tipo_periodo" class="form-label">Período</label>
                        <select id="tipo_periodo" name="tipo_periodo" class="form-control" required>
                            <option value="todo" <?= set_select('tipo_periodo', 'todo', old('tipo_periodo', $_GET['tipo_periodo'] ?? 'todo') === 'todo') ?>>
                                Todo o período
                            </option>
                            <option value="especifico" <?= set_select('tipo_periodo', 'especifico', old('tipo_periodo', $_GET['tipo_periodo'] ?? '') === 'especifico') ?>>
                                Período específico
                            </option>
                        </select>
                    </div>

                    <!-- Datas (escondidas por padrão) -->
                    <div class="col-md-3 div-datas" style="display: none;">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control"
                            value="<?= set_value('data_inicio', $_GET['data_inicio'] ?? '') ?>" max="<?=date('Y-m-d')?>">
                    </div>

                    <div class="col-md-3 div-datas" style="display: none;">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control"
                            value="<?= set_value('data_fim', $_GET['data_fim'] ?? '') ?>" max="<?=date('Y-m-d')?>">
                    </div>

                    <!-- Botão -->
                    <div class="col-md-12 text-end">
                        <button type="submit" id="sendFormRelatorio" class="btn btn-primary mt-3">
                            <i class="fe fe-search me-2"></i> buscar
                        </button>
                    </div>
                </div>
            </form>

            </div>
        </div>
    </div>
</div>

<!-- Resultado: Tabela de movimentações -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <?php if (isset($produtos) && !empty($produtos)) : ?>
                    <?php foreach ($produtos as $produto) : ?>
                        <h5>
                            <span style="display:inline-block;width:15px;height:15px;background-color:<?=getHexaCorProduto($produto)?>;margin-right:5px;border:1px solid #000;border-radius:3px;"></span>
                            <?= esc($produto['codigo']) ?> | <?= esc($produto['nome']) ?> 
                            <small>(<?=getNomeCorProduto($produto)?>)</small>
                            <br>
                            <small class="text-muted">(<?= esc($produto['categoria']) ?>)</small>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Data</th>
                                        <th>Tipo</th>
                                        <th>Quantidade</th>
                                        <th>Usuário</th>
                                        <th>Saldo após</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $saldo = 0;
                                    $contador = 1;
                                    foreach ($movimentos as $movimento) :
                                        if ($movimento['id_produto'] != $produto['id_produto']) continue;

                                        // Atualiza saldo
                                        if ($movimento['tipo'] === 'entrada') {
                                            $saldo += $movimento['quantidade'];
                                            $badge = '<span class="badge bg-success">' . esc($movimento['motivo']) . '</span>';
                                        } else {
                                            $saldo -= $movimento['quantidade'];
                                            $badge = '<span class="badge bg-danger">' . esc($movimento['motivo']) . '</span>';
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $contador++ ?></td>
                                            <td><?= date('d/m/Y', strtotime($movimento['data'])) ?></td>
                                            <td><?= $badge ?></td>
                                            <td><?= esc($movimento['quantidade']) ?></td>
                                            <td><?= esc($movimento['usuario']) ?></td>
                                            <td><?= $saldo ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <td colspan="5" class="text-end"><strong>Saldo</strong></td>
                                        <td><strong><?= $saldo ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>- Nenhum produto encontrado -</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
    
        $('#tipo_periodo').on('change', function () {
            const isEspecifico = $(this).val() === 'especifico';
            $('.div-datas').toggle(isEspecifico);
            $('#data_inicio, #data_fim').prop('required', isEspecifico);
        });

        // Inicializa o estado correto ao carregar a página
        $('#tipo_periodo').trigger('change');

        function validarIntervaloDatas() {
            const inicio = $('#data_inicio').val();
            const fim = $('#data_fim').val();

            if (inicio && fim && fim < inicio) {
                alert('A data final não pode ser menor que a data inicial.');
                $('#data_fim').addClass('is-invalid').val('').focus();
            }
        }

        $('#data_fim').on('change', validarIntervaloDatas);



        $("#formRelatorio").submit(function(e){
            
            $("#sendFormRelatorio").loading();
        });
        
    });
</script>
<?= $this->endSection() ?>
