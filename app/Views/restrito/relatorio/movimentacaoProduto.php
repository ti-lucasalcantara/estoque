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
                <h5 class="mt-4">Álcool em Gel 70%</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Usuário</th>
                                <th>Observações</th>
                                <th>Saldo após</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2024-06-01</td>
                                <td><span class="badge bg-success">Entrada manual</span></td>
                                <td>50</td>
                                <td>João Souza</td>
                                <td>Reposição inicial</td>
                                <td>50</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2024-06-03</td>
                                <td><span class="badge bg-danger">Saída por consumo</span></td>
                                <td>10</td>
                                <td>Maria Clara</td>
                                <td>Uso interno</td>
                                <td>40</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>2024-06-04</td>
                                <td><span class="badge bg-success">Entrada manual</span></td>
                                <td>20</td>
                                <td>Lucas Alcântara</td>
                                <td>Nova compra</td>
                                <td>60</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>2024-06-06</td>
                                <td><span class="badge bg-danger">Saída por transferência</span></td>
                                <td>15</td>
                                <td>João Souza</td>
                                <td>Transferência de estoque</td>
                                <td>45</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>2024-06-07</td>
                                <td><span class="badge bg-danger">Saída por consumo</span></td>
                                <td>5</td>
                                <td>Aline Castro</td>
                                <td>Distribuição</td>
                                <td>40</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="6" class="text-end"><strong>Saldo atual</strong></td>
                                <td><strong>40</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h5 class="mt-4">Luvas de Procedimento</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Usuário</th>
                                <th>Origem/Destino</th>
                                <th>Observações</th>
                                <th>Saldo após</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2024-06-02</td>
                                <td><span class="badge bg-success">Entrada manual</span></td>
                                <td>100</td>
                                <td>Lucas Alcântara</td>
                                <td>Fornecedor: MedPro</td>
                                <td>Compra inicial</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2024-06-03</td>
                                <td><span class="badge bg-danger">Saída por consumo</span></td>
                                <td>30</td>
                                <td>Maria Clara</td>
                                <td>Setor: Fiscalização</td>
                                <td>Uso diário</td>
                                <td>70</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>2024-06-04</td>
                                <td><span class="badge bg-success">Entrada por devolução</span></td>
                                <td>10</td>
                                <td>João Souza</td>
                                <td>Setor: Enfermaria</td>
                                <td>Retorno de material</td>
                                <td>80</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>2024-06-05</td>
                                <td><span class="badge bg-danger">Saída para descarte</span></td>
                                <td>5</td>
                                <td>Aline Castro</td>
                                <td>Lixo hospitalar</td>
                                <td>Pacote danificado</td>
                                <td>75</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>2024-06-06</td>
                                <td><span class="badge bg-danger">Saída por consumo</span></td>
                                <td>15</td>
                                <td>Lucas Alcântara</td>
                                <td>Setor: Atendimento</td>
                                <td>Distribuição de rotina</td>
                                <td>60</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="7" class="text-end"><strong>Saldo atual</strong></td>
                                <td><strong>60</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
