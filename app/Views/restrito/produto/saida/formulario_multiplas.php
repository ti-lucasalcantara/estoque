<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">saidas Múltiplas de Produtos</h4>
    </div>
</div>

<form method="POST" action="<?=url_to('restrito.saida.salvarMultiplas')?>" id="formsaidasMultiplas">
    <div class="card">
        <div class="card-body">

            <!-- Campos gerais -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-bold">*Data da saida</label>
                    <input type="date" name="data_saida" class="form-control"  max="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">*Localização</label>
                    <select name="localizacao" class="form-select" >
                        <option value="">Selecione</option>
                        <option value="Loja">Loja</option>
                        <option value="Depósito 1">Depósito 1</option>
                        <option value="Depósito 2">Depósito 2</option>
                    </select>
                </div>
            </div>

            <!-- Linhas dinâmicas de produtos -->
            <div id="linhassaidas">
                <div class="row saida-linha mb-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">*Produto</label>
                        <select name="produtos[0][id_produto]" class="form-select select-produto" >
                            <option value="">Selecione</option>
                            <?php foreach ($produtos as $p): ?>
                                <option value="<?= $p['id_produto'] ?>"><?= esc($p['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">*Quantidade</label>
                        <input type="number" name="produtos[0][quantidade]" class="form-control" >
                    </div>

                    <div class="col-md-1 d-flex gap-2">
                        <button type="button" class="btn btn-outline-success w-50 btn-add-linha">
                            <i class="fe fe-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fe fe-check me-2"></i>Registrar Todas as saidas
                </button>
            </div>

        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let index = 1;

function atualizarSelectsDisponiveis() {
    const todosSelecionados = [...document.querySelectorAll('.select-produto')]
        .map(select => select.value)
        .filter(v => v !== "");

    document.querySelectorAll('.select-produto').forEach(select => {
        const valorAtual = select.value;

        [...select.options].forEach(option => {
            if (option.value === "" || option.value === valorAtual) {
                option.disabled = false;
            } else {
                option.disabled = todosSelecionados.includes(option.value);
            }
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

        // Atualiza os nomes dos inputs
        novaLinha.querySelectorAll('input, select').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
            input.value = '';
        });

        // Configura os botões
        const btnArea = novaLinha.querySelector('.col-md-1');
        btnArea.innerHTML = `
            <button type="button" class="btn btn-outline-success w-50 btn-add-linha me-1">
                <i class="fe fe-plus"></i>
            </button>
            <button type="button" class="btn btn-outline-danger w-50 btn-remove-linha">
                <i class="fe fe-trash"></i>
            </button>
        `;

        document.querySelector('#linhassaidas').appendChild(novaLinha);
        index++;

        atualizarSelectsDisponiveis();
    }

    if (e.target.closest('.btn-remove-linha')) {
        e.preventDefault();
        const linha = e.target.closest('.saida-linha');
        linha.remove();
        atualizarSelectsDisponiveis();
    }
});
</script>
<?= $this->endSection() ?>
