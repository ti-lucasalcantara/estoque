<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Produtos</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.produto.formulario')?>" class="btn btn-outline-primary">
                <i class="fe fe-plus me-2"></i>
                Cadastrar Produto
            </a>
        </div>
    </div>
</div>

<div class="text-end mb-3">
    <button id="view-cards" class="btn btn-primary btn-sm">Ver em Cards</button>
    <button id="view-table" class="btn btn-outline-primary btn-sm">Ver em Tabela</button>
</div>
<!--End Page header-->
<?php
if(isset($tb_produto) && !empty($tb_produto)){
?>
<!-- Row -->
<div id="cards-view">
    <div class="row" >
        <?php
        foreach ($tb_produto as $produto) {
        ?>
            <div class="col-md-3 col-lg-3">
                <div class="card produto-card">
                    <div class="image-container position-relative">
                        <img src="<?=$produto['imagemPrincipal'] ?? '/assets/images/photos/8.jpg'?>" alt="image" class="card-image1">
                        
                        <div class="image-overlay">
                            <h5 class="overlay-title"><?=$produto['nome']?></h5>
                            <p class="overlay-text"><?=$produto['codigo']?></p>
                        </div>

                        <div class="dropdown action-menu">
                            <button class="btn btn-sm btn-outline-primary text-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-cogs"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?=url_to('restrito.produto.editar', base64_encode($produto['id_produto']))?>">Editar</a></li>
                                <li><a class="dropdown-item modalExcluir" href="javascript:void(0);" data-id-excluir="<?=$produto['id_produto']?>" data-url-excluir="<?=url_to('restrito.produto.excluir')?>" data-mensagem-excluir="Confirma excluir o produto [<?=$produto['nome']?>] ?">Excluir</a></li>
                                <li><a class="dropdown-item" href="<?=url_to('restrito.entrada.formulario', base64_encode($produto['id_produto']))?>">Entrada</a></li>
                                <li><a class="dropdown-item" href="<?=url_to('restrito.saida.formulario', base64_encode($produto['id_produto']))?>">Saída</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body text-center">
                        <h6 class="estoque-label <?=$produto['saldoEstoque'] <= $produto['estoque_minimo'] ? 'text-danger' : ''?>">Em Estoque</h6>
                        <p class="estoque-quantidade <?=$produto['saldoEstoque'] <= $produto['estoque_minimo'] ? 'text-danger' : ''?>"><?=$produto['saldoEstoque'];?></p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<!-- End Row -->

<!-- Row -->
<div class="row" id="table-view" style="display:none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="tabela_produtos" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>CÓDIGO</th>
                            <th>PRODUTO</th>
                            <th>CATEGORIA</th>
                            <th>EM ESTOQUE</th>
                            <th></th>
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
                            <th>EM ESTOQUE</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            foreach ($tb_produto as $produto) {
                        ?>
                        <tr>
                            <td><?=$produto['codigo']?></td>
                            <td><?=$produto['nome']?></td>
                            <td><?=$produto['categoria']?></td>
                            <td><?=$produto['saldoEstoque'];?></td>
                            <td><a href="<?=url_to('restrito.produto.editar', base64_encode($produto['id_produto']))?>">editar</a></td>
                            <td><a href="javascript:void(0);" data-id-excluir="<?=$produto['id_produto']?>" data-url-excluir="<?=url_to('restrito.produto.excluir')?>" data-mensagem-excluir="Confirma excluir o produto [<?=$produto['nome']?>] ?" class="modalExcluir">excluir</a></td>
                            <td><a href="<?=url_to('restrito.entrada.formulario', base64_encode($produto['id_produto']))?>">entrada</a></td>
                            <td><a href="<?=url_to('restrito.saida.formulario', base64_encode($produto['id_produto']))?>">saida</a></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<?php
}
?>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<style>
.produto-card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.image-container {
    position: relative;
}

.card-image1 {
    width: 100%;
    height: 250px;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    bottom: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.7);
    color: #fff;
    text-align: center;
    padding: 8px 5px;
}

.overlay-title {
    font-size: 16px;
    margin: 0;
}

.overlay-text {
    font-size: 13px;
    margin: 0;
}

.action-menu {
    position: absolute;
    top: 10px;
    right: 10px;
}


.card-body {
    padding: 15px;
}

.estoque-label {
    font-size: 14px;
    color: #888;
    margin-bottom: 5px;
}

.estoque-quantidade {
    font-size: 22px;
    font-weight: bold;
    color: #333;
}


</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>


document.addEventListener('DOMContentLoaded', function () {
    const cardsView = document.getElementById('cards-view');
    const tableView = document.getElementById('table-view');
    const btnCards = document.getElementById('view-cards');
    const btnTable = document.getElementById('view-table');

    // Função para mudar visualização
    function switchView(view) {
        if (view === 'cards') {
            cardsView.style.display = 'block';
            tableView.style.display = 'none';
            btnCards.classList.add('btn-primary');
            btnCards.classList.remove('btn-outline-primary');
            btnTable.classList.add('btn-outline-primary');
            btnTable.classList.remove('btn-primary');
        } else {
            cardsView.style.display = 'none';
            tableView.style.display = 'block';
            btnCards.classList.add('btn-outline-primary');
            btnCards.classList.remove('btn-primary');
            btnTable.classList.add('btn-primary');
            btnTable.classList.remove('btn-outline-primary');
        }
        // Salvar no localStorage
        localStorage.setItem('preferredView', view);
    }

    // Verificar a preferência salva
    const savedView = localStorage.getItem('preferredView') || 'cards';
    switchView(savedView);

    // Eventos dos botões
    btnCards.addEventListener('click', () => switchView('cards'));
    btnTable.addEventListener('click', () => switchView('table'));
});

$('#tabela_produtos').DataTable({
    "scrollY": "800px", // Define a altura máxima com scroll
    "scrollCollapse": true, // Mantém a tabela ajustada ao conteúdo
    "paging": false, // Desativa a paginação
    "ordering": false, // Ativa a ordenação
    "info": false, // Oculta informações de registros (opcional)
}); 
</script>
<?= $this->endSection() ?>
