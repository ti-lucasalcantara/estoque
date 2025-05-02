<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary"><i class="fa fa-cubes"></i> Produtos</h4>
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

<div class="d-flex justify-content-between mb-3">
    <input type="text" id="filtro-produto" class="form-control form-control w-80" placeholder="Buscar o produto pelo NOME, CÓDIGO ou COR">
    <div>
        <button id="view-cards" class="btn btn-primary btn-sm">Ver em Cards</button>
        <button id="view-table" class="btn btn-outline-primary btn-sm">Ver em Tabela</button>
    </div>
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
                                <li><a class="dropdown-item" href="<?=url_to('restrito.entrada.formulario', base64_encode($produto['id_produto']))?>"><i class="fa fa-sign-in"></i> Entrada</a></li>
                                <li><a class="dropdown-item" href="<?=url_to('restrito.saida.formulario', base64_encode($produto['id_produto']))?>"><i class="fa fa-sign-out"></i> Saída</a></li>
                                <li><a class="dropdown-item" href="<?=url_to('restrito.produto.editar', base64_encode($produto['id_produto']))?>"><i class="fa fa-pencil"></i> Editar</a></li>
                                <li><a class="dropdown-item modalExcluir" href="javascript:void(0);" data-id-excluir="<?=$produto['id_produto']?>" data-url-excluir="<?=url_to('restrito.produto.excluir')?>" data-mensagem-excluir="Confirma excluir o produto [<?=$produto['nome']?>] ?"><i class="fa fa-trash"></i> Excluir</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body text-center d-flex w-100" style="justify-content: space-between; align-items: center;">
                        <div>
                            <span class="estoque-label <?=$produto['saldoEstoque'] <= $produto['estoque_minimo'] ? 'text-danger' : ''?>">Em Estoque</span>
                            <br>
                            <span class="estoque-quantidade <?=$produto['saldoEstoque'] <= $produto['estoque_minimo'] ? 'text-danger' : ''?>"><?=$produto['saldoEstoque'];?></span>
                        </div>
                        <?php
                        if(!empty(getHexaCorProduto($produto))){
                        ?>
                        <div class="text-center">
                            <span><?=getNomeCorProduto($produto)?></span>
                            <br>
                            <span style="display: inline-block; width: 20px; height: 20px; background-color:<?=getHexaCorProduto($produto)?>; border: 1px solid #000; border-radius: 3px; margin-left: 5px;"></span>
                        </div>
                        <?php
                        }
                        ?>
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



    // Filtro de produtos
    const filtroInput = document.getElementById('filtro-produto');
    const cards = document.querySelectorAll('#cards-view .col-md-3');

    filtroInput.addEventListener('input', function() {
        const filtro = this.value.toLowerCase();

        cards.forEach(function(card) {
            const nome = card.querySelector('.overlay-title').textContent.toLowerCase();
            const codigo = card.querySelector('.overlay-text').textContent.toLowerCase();

            // Se tiver cor no card, pega o texto dela (cuidado com cards sem cor!)
            const corElement = card.querySelector('.card-body .text-center span:first-child');
            const cor = corElement ? corElement.textContent.toLowerCase() : '';

            if (nome.includes(filtro) || codigo.includes(filtro) || cor.includes(filtro)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

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
