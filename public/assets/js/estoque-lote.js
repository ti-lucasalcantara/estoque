(() => {
    const linhas = document.getElementById('lote-linhas');
    const modelo = document.getElementById('lote-modelo');
    const adicionar = document.getElementById('lote-adicionar');
    const salvar = document.getElementById('lote-salvar');
    const podeSalvar = !salvar.disabled;
    let indice = linhas.children.length;

    function mostrarProduto(opcao) {
        const cor = opcao.element?.dataset.cor?.trim();
        if (!cor || !/^#(?:[a-f\d]{3}|[a-f\d]{6})$/i.test(cor)) return opcao.text;
        const rotulo = document.createElement('span');
        const amostra = document.createElement('span');
        amostra.className = 'color-swatch';
        amostra.style.backgroundColor = cor;
        amostra.setAttribute('aria-hidden', 'true');
        rotulo.append(amostra, opcao.text);
        return $(rotulo);
    }

    $('#form-lote [data-lote-select]').each(function () {
        $(this).select2({width: '100%', placeholder: this.options[0].text});
    });
    $('#form-lote').on('select2:open', 'select', () => {
        requestAnimationFrame(() => document.querySelector('.select2-container--open .select2-search__field')?.focus());
    });

    const preparar = linha => {
        $(linha.querySelector('select')).select2({
            width: '100%',
            placeholder: 'Selecione um produto',
            templateResult: mostrarProduto,
            templateSelection: mostrarProduto
        }).on('change', resumir);
    };
    function resumir() {
        let unidades = 0;
        let produtos = 0;
        for (const linha of linhas.children) {
            linha.querySelector('[data-batch-remove]').disabled = linhas.children.length === 1;
            const quantidade = linha.querySelector('input').valueAsNumber;
            if (linha.querySelector('select').value && Number.isInteger(quantidade) && quantidade > 0) {
                produtos++;
                unidades += quantidade;
            }
        }
        document.getElementById('lote-resumo').textContent = produtos + (produtos === 1 ? ' linha' : ' linhas') + ' · ' + unidades + (unidades === 1 ? ' unidade' : ' unidades');
    }
    for (const linha of linhas.children) preparar(linha);
    adicionar.addEventListener('click', () => {
        const fragmento = modelo.content.cloneNode(true);
        for (const elemento of fragmento.querySelectorAll('[id], [name], [for]')) {
            for (const atributo of ['id', 'name', 'for']) {
                if (elemento.hasAttribute(atributo)) elemento.setAttribute(atributo, elemento.getAttribute(atributo).replace('INDICE', String(indice)));
            }
        }
        indice++;
        const linha = fragmento.firstElementChild;
        linhas.append(fragmento);
        preparar(linha);
        $(linha.querySelector('select')).select2('open');
        resumir();
    });
    linhas.addEventListener('click', evento => {
        const remover = evento.target.closest('[data-batch-remove]');
        if (!remover || linhas.children.length <= 1) return;
        const linha = remover.closest('[data-batch-line]');
        $(linha.querySelector('select')).select2('destroy');
        linha.remove();
        adicionar.focus();
        resumir();
    });
    linhas.addEventListener('input', resumir);
    document.getElementById('form-lote').addEventListener('submit', () => {
        salvar.disabled = true;
        salvar.dataset.label = salvar.textContent;
        salvar.textContent = 'Registrando…';
    });
    window.addEventListener('pageshow', () => {
        salvar.disabled = !podeSalvar;
        if (salvar.dataset.label) salvar.textContent = salvar.dataset.label;
    });
    resumir();
})();
