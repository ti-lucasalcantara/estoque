<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Saidas Múltiplas de Produtos</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <a href="<?=url_to('restrito.saida.index')?>" class="btn btn-outline-primary">
                <i class="fe fe-chevron-left me-2"></i>
                Voltar
            </a>
        </div>
    </div>
</div>

<form method="POST" action="<?=url_to('restrito.saida.salvarMultiplas')?>" id="formSaidasMultiplas">
    <div class="card">
        <div class="card-body">

            <!-- Campos gerais -->
            <div class="row mb-4">
                
                <div class="col-md-2">
                    <label for="data_saida" class="form-label">*Data da Saida</label>
                    <input type="date" class="form-control <?= empty(validation_show_error('data_saida')) ? '' : 'is-invalid' ?>" id="data_saida" name="data_saida" value="<?= set_value('data_saida', (isset($produto_saida['data_saida']) ? (date('Y-m-d',strtotime($produto_saida['data_saida']))) : '')    ) ?>" max="<?=date('Y-m-d')?>">
                    <small class="text-danger"><?= validation_show_error('data_saida') ?></small>
                </div>
                
                <div class="col-md-5">
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
                 
                <div class="col-md-5">
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

            <!-- Linhas dinâmicas de produtos -->
            <div id="linhasSaidas">
                <div class="row saida-linha mb-3 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label">*Produto</label>
                        <select name="produtos[0][id_produto][]" class="form-select select-produto" multiple>
                            <?php foreach ($produtos as $p): ?>
                                <option value="<?= $p['id_produto'] ?>"
                                        data-nome="<?= esc($p['nome']) ?>"
                                        data-codigo="<?= esc($p['codigo']) ?>"
                                        data-cor="<?= esc(getNomeCorProduto($p)) ?>"
                                        data-hexa="<?= esc(getHexaCorProduto($p)) ?>">
                                    <?= esc($p['nome']) ?> (<?= esc($p['codigo']) ?>) - <?= esc(getNomeCorProduto($p)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">*Quantidade</label>
                        <input type="number" name="produtos[0][quantidade]" class="form-control" placeholder="Ex: 100">
                    </div>

                    <div class="col-md-1 d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary w-100 btn-add-linha">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fe fe-check me-2"></i>Registrar Todas as Saidas
                </button>
            </div>

        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    inicializarSelect2();
    atualizarSelectsDisponiveis();
});

let index = 1;

function atualizarSelectsDisponiveis() {
    // Coletar todos os valores selecionados em todas as linhas
    let todosSelecionados = [];
    document.querySelectorAll('.select-produto').forEach(select => {
        [...select.selectedOptions].forEach(opt => {
            if (opt.value) {
                todosSelecionados.push(opt.value);
            }
        });
    });

    document.querySelectorAll('.select-produto').forEach(select => {
        const valoresDestaLinha = [...select.selectedOptions].map(opt => opt.value);

        [...select.options].forEach(option => {
            if (option.value === "") return;

            // Se a opção está selecionada em outra linha, desabilita
            if (todosSelecionados.includes(option.value) && !valoresDestaLinha.includes(option.value)) {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });

        // Atualiza visualmente o Select2
        $(select).select2('destroy').select2({
            templateResult: formatSelectOption,
            templateSelection: formatSelectSelection,
            width: '100%',
            placeholder: 'Selecione os produtos'
        });
    });
}

document.addEventListener('change', function (e) {
    if (e.target.classList.contains('select-produto')) {
        atualizarSelectsDisponiveis();
    }
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-add-linha')) {
        e.preventDefault();

        const linhaAtual = e.target.closest('.saida-linha');
        const novaLinha = linhaAtual.cloneNode(true);

        novaLinha.querySelectorAll('input, select').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
            if (input.tagName.toLowerCase() === 'select') {
                $(input).val(null);
            } else {
                input.value = '';
            }
        });

        $(novaLinha).find('.select2-container').remove();

        const btnArea = novaLinha.querySelector('.col-md-1');
        btnArea.innerHTML = `
            <button type="button" class="btn btn-outline-primary w-100 btn-add-linha">
                <i class="fa fa-plus"></i>
            </button>
            <button type="button" class="btn btn-outline-danger w-100 btn-remove-linha">
                <i class="fa fa-trash"></i>
            </button>
        `;

        document.querySelector('#linhasSaidas').appendChild(novaLinha);
        index++;

        inicializarSelect2();
        atualizarSelectsDisponiveis();
    }

    if (e.target.closest('.btn-remove-linha')) {
        e.preventDefault();
        const linha = e.target.closest('.saida-linha');
        linha.remove();
        atualizarSelectsDisponiveis();
    }
});

function inicializarSelect2() {
    $('.select-produto').select2({
        templateResult: formatSelectOption,
        templateSelection: formatSelectSelection,
        width: '100%',
        placeholder: 'Selecione os produtos'
    });
}

function formatSelectOption(data) {
    if (!data.id) return data.text;

    const $option = $(data.element);
    const nome = $option.data('nome');
    const codigo = $option.data('codigo');
    const cor = $option.data('cor');
    const hexa = $option.data('hexa');

    return $(`
        <span>
            <span style="display:inline-block;width:15px;height:15px;background-color:${hexa};margin-right:5px;border:1px solid #000;border-radius:3px;"></span>
            ${nome} (${codigo}) - ${cor}
        </span>
    `);
}

function formatSelectSelection(data) {
    if (!data.id) return data.text;

    const $option = $(data.element);
    const nome = $option.data('nome');
    const codigo = $option.data('codigo');
    const cor = $option.data('cor');

    return `${nome} (${codigo}) - ${cor}`;
}
</script>
<?= $this->endSection() ?>