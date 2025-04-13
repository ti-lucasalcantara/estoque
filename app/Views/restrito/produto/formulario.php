<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Novo Produto</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.produto.index')?>" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>
                Voltar
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
                <form id="formCadastroProduto">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do produto" required>
                        </div>
                        <div class="col-md-4">
                            <label for="categoria" class="form-label">Categoria</label>
                            <div class="input-group">
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Selecione</option>
                                    <option value="eletronico">Eletrônico</option>
                                    <option value="vestuario">Vestuário</option>
                                    <option value="alimento">Alimento</option>
                                    <!-- Outras opções -->
                                </select>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                                    <i class="fa fa-cog"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="preco" class="form-label">Preço (R$)</label>
                            <input type="number" class="form-control" id="preco" name="preco" placeholder="0,00" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descrição do produto"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
                    </div>
                </form>
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
