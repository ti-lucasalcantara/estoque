<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Novo Produto</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.produto.index')?>" class="btn btn-outline-primary">
                <i class="fe fe-chevron-left me-2"></i>
                Voltar
            </a>
        </div>
    </div>
</div>
<!--End Page header-->
<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form enctype="multipart/form-data" id="formCadastroProduto" method="POST" action="<?=url_to('restrito.produto.salvar')?>" novalidate autocomplete="off">
                    <div class="row">
                        <div class="col-md-9">
                            <?php
                            if(isset($produto) && !empty($produto['id_produto'])){
                            ?>
                            <input type="hidden" name="id_produto" value="<?=base64_encode($produto['id_produto'] ?? '')?>">
                            <?php
                            }
                            ?>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="codigo" class="form-label">*Código</label>
                                    <input type="text" class="form-control <?= empty(validation_show_error('codigo')) ? '' : 'is-invalid' ?>" id="codigo" name="codigo" placeholder="Código" value="<?= set_value('codigo', ($produto['codigo'] ?? '') ) ?>">
                                    <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('codigo') ?></small>
                                </div>
                                <div class="col-md-10">
                                    <label for="nome" class="form-label">*Nome do Produto</label>
                                    <input type="text" class="form-control <?= empty(validation_show_error('nome')) ? '' : 'is-invalid' ?>" id="nome" name="nome" placeholder="Digite o nome do produto" value="<?= set_value('nome', ($produto['nome'] ?? '')) ?>">
                                    <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('nome') ?></small>
                                </div>

                                
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-9">
                                    <label for="id_categoria" class="form-label">*Categoria</label>
                                    <div class="input-group">
                                        <select class="form-select <?= empty(validation_show_error('id_categoria')) ? '' : 'is-invalid' ?>" id="id_categoria" name="id_categoria">
                                            <option value="">Selecione</option>
                                            <?php
                                            if(isset($ref_categoria) && !empty($ref_categoria)){
                                                foreach ($ref_categoria as $categoria) {
                                            ?>
                                            <option value="<?=$categoria['id_categoria']?>" <?= set_select('id_categoria', $categoria['id_categoria'], (isset($produto['id_categoria']) && $produto['id_categoria'] == $categoria['id_categoria'] ? true : false) )?>><?=$categoria['categoria']?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <!-- Outras opções -->
                                        </select>
                                        <button data-bs-toggle="modal" data-bs-target="#modalRefCategoria" type="button" class="btn btn-outline-secondary">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                    </div>
                                    <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('id_categoria') ?></small>
                                </div>

                                <div class="col-md-3">
                                    <label for="estoque_minimo" class="form-label">*Estoque Minímo</label>
                                    <input type="number" class="form-control <?= empty(validation_show_error('estoque_minimo')) ? '' : 'is-invalid' ?>" id="estoque_minimo" name="estoque_minimo" placeholder="Ex.: 20" value="<?= set_value('estoque_minimo', ($produto['estoque_minimo'] ?? '')) ?>">
                                    <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('estoque_minimo') ?></small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea oninput="atualizarContador()" maxlength="500" class="form-control <?= empty(validation_show_error('descricao')) ? '' : 'is-invalid' ?>" id="descricao" name="descricao" rows="3" placeholder="Descrição do produto" style="resize:none"><?=set_value('descricao', ($produto['descricao'] ?? ''))?></textarea>
                                    <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('descricao') ?></small>
                                    <small id="contadorDescricao" class="text-muted">Restam 500 caracteres</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="image-upload-box" id="imageUploadBox">
                                <?php if (isset($produto['imagemPrincipal']) && !empty($produto['imagemPrincipal'])): ?>
                                    <img src="<?= $produto['imagemPrincipal'] ?>" alt="Imagem do Produto" id="previewImage" class="img-fluid">
                                <?php else: ?>
                                    <label for="uploadPrompt">Clique para carregar imagem</label>
                                <?php endif; ?>
                            </div>
                            <input type="file" id="uploadPrompt" name="imagem" accept="image/*" style="display: none;">
                        </div>

                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <?php
                                if(isset($produto) && !empty($produto['id_produto'])){
                                ?>
                                  <button type="submit" class="btn btn-success col-3"><i class="fa fa-refresh"></i> Alterar Produto</button>
                                <?php
                                }else{
                                ?>
                                <button type="submit" class="btn btn-success col-3"><i class="fa fa-check"></i> Cadastrar Produto</button>
                                <?php
                                }
                                ?>
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<?= $this->include('_componentes/ref_categoria') ?>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
.image-upload-box {
    width: 100%;
    aspect-ratio: 1 / 1; /* Quadrado perfeito */
    border: 2px dashed #ccc;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    position: relative;
    background-color: #f8f9fa;
    transition: border-color 0.3s;
}
.image-upload-box:hover {
    border-color: #007bff;
}
.image-upload-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain; /* <<< IMPORTANTE: mostra a imagem toda */
}

#uploadPrompt {
    font-size: 14px;
    color: #888;
    text-align: center;
    padding: 10px;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atualizar contador de caracteres
    const textarea = document.getElementById('descricao');
    const contador = document.getElementById('contadorDescricao');
    const maxCaracteres = 500;

    function atualizarContador() {
        const caracteresRestantes = maxCaracteres - textarea.value.length;
        if (caracteresRestantes >= 0) {
            contador.textContent = `Restam ${caracteresRestantes} caracteres`;
        } else {
            contador.textContent = `Texto muito longo. O limite é de 500 caracteres.`;
        }
    }

    if (textarea && contador) {
        atualizarContador(); // Atualizar na carga inicial
        textarea.addEventListener('input', atualizarContador); // Atualizar ao digitar
    }

    // Upload de imagem
    const box = document.getElementById('imageUploadBox');
    const input = document.getElementById('uploadPrompt');

    if (box && input) {
        box.addEventListener('click', function(e) {
            if (e.target.tagName.toLowerCase() !== 'input') {
                input.click();
            }
        });

        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let existingImage = document.getElementById('previewImage');
                    if (!existingImage) {
                        existingImage = document.createElement('img');
                        existingImage.id = 'previewImage';
                        existingImage.className = 'img-fluid';
                        box.innerHTML = '';
                        box.appendChild(existingImage);
                    }
                    existingImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
