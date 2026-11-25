(() => {
    const formulario = document.getElementById('form-relatorio');
    const categorias = $('#ref_categoria');
    const produtos = $('#tb_produtos');
    const opcoes = [...produtos[0].options];
    const periodo = document.getElementById('tipo_periodo');

    function rotuloProduto(opcao) {
        const cor = opcao.element?.dataset.cor;
        if (!cor || !/^#(?:[a-f\d]{3}|[a-f\d]{6})$/i.test(cor)) return opcao.text;
        const rotulo = document.createElement('span');
        const amostra = document.createElement('span');
        amostra.className = 'color-swatch';
        amostra.style.backgroundColor = cor;
        amostra.setAttribute('aria-hidden', 'true');
        rotulo.append(amostra, opcao.text.trim());
        return $(rotulo);
    }

    categorias.select2({width: '100%', placeholder: 'Todas as categorias'});
    produtos.select2({width: '100%', placeholder: 'Todos os produtos', templateResult: rotuloProduto, templateSelection: rotuloProduto});

    function atualizarProdutos() {
        const selecionadas = new Set(categorias.val() ?? []);
        const produtosSelecionados = new Set(produtos.val() ?? []);
        const disponiveis = opcoes.filter(opcao => !selecionadas.size || selecionadas.has(opcao.dataset.categoria));
        produtos[0].replaceChildren(...disponiveis);
        for (const opcao of disponiveis) opcao.selected = produtosSelecionados.has(opcao.value);
        produtos.trigger('change');
    }

    categorias.on('change', atualizarProdutos);
    atualizarProdutos();

    if (periodo) {
        const inicio = formulario.elements.data_inicio;
        const fim = formulario.elements.data_fim;
        function atualizarPeriodo() {
            const intervalo = periodo.value === 'especifico';
            for (const campo of [inicio, fim]) {
                campo.closest('[data-datas-relatorio]').hidden = !intervalo;
                campo.disabled = !intervalo;
                campo.required = intervalo;
            }
            fim.setCustomValidity(intervalo && inicio.value && fim.value && inicio.value > fim.value
                ? 'A data final deve ser igual ou posterior à inicial.' : '');
        }

        formulario.addEventListener('input', atualizarPeriodo);
        periodo.addEventListener('change', atualizarPeriodo);
        atualizarPeriodo();
    }
})();
