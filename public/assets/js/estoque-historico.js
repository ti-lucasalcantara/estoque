(() => {
    const formulario = document.getElementById('periodo-historico');
    const inicio = formulario.elements.data_inicio;
    const fim = formulario.elements.data_fim;
    const busca = document.getElementById('buscar-historico');
    const avisoPeriodo = document.getElementById('erro-periodo');
    const vazio = document.getElementById('historico-vazio');
    const totais = document.getElementById('totais-historico');
    const chave = 'estoque.historico.' + formulario.dataset.tipo;
    const url = new URL(location.href);
    const temPeriodoNaUrl = url.searchParams.has('data_inicio') || url.searchParams.has('data_fim');
    const numero = new Intl.NumberFormat('pt-BR');
    const normalizar = valor => valor.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
    const grupos = [...document.querySelectorAll('[data-dia-historico]')].map(secao => ({
        secao,
        registros: [...secao.querySelectorAll('[data-busca-historico]')].map(item => ({
            item, texto: normalizar(item.dataset.buscaHistorico), quantidade: Number(item.dataset.quantidade)
        })),
    }));

    try {
        busca.value = localStorage.getItem(chave + '.busca') ?? '';
        if (!temPeriodoNaUrl) {
            const periodo = JSON.parse(localStorage.getItem(chave + '.periodo') ?? 'null');
            const dataValida = valor => typeof valor === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(valor)
                && Number.isFinite(Date.parse(valor)) && new Date(valor).toISOString().slice(0, 10) === valor;
            if (periodo && dataValida(periodo.inicio) && dataValida(periodo.fim)
                && periodo.inicio <= periodo.fim && periodo.fim <= formulario.dataset.fimPadrao
                && (periodo.inicio !== inicio.value || periodo.fim !== fim.value)) {
                url.searchParams.set('data_inicio', periodo.inicio);
                url.searchParams.set('data_fim', periodo.fim);
                location.replace(url.href);
            }
        }
    } catch {}

    function conferirPeriodo() {
        const invertido = Boolean(inicio.value && fim.value && inicio.value > fim.value);
        avisoPeriodo.hidden = !invertido;
        fim.setCustomValidity(invertido ? avisoPeriodo.textContent : '');
    }

    function aplicarBusca() {
        const termos = normalizar(busca.value).split(/\s+/).filter(Boolean);
        let total = 0;
        let unidades = 0;
        for (const {secao, registros} of grupos) {
            let visiveis = 0;
            for (const {item, texto, quantidade} of registros) {
                item.hidden = !termos.every(termo => texto.includes(termo));
                if (!item.hidden) {
                    visiveis++;
                    unidades += quantidade;
                }
            }
            total += visiveis;
            secao.hidden = visiveis === 0;
        }

        if (grupos.length) vazio.textContent = 'Nenhuma movimentação corresponde à busca neste período.';
        vazio.hidden = total > 0;
        totais.textContent = numero.format(total) + (total === 1 ? ' lançamento' : ' lançamentos') + ' · ' + numero.format(unidades) + ' un.';
    }

    formulario.addEventListener('input', conferirPeriodo);
    formulario.addEventListener('submit', evento => {
        conferirPeriodo();
        if (!formulario.reportValidity()) {
            evento.preventDefault();
            return;
        }
        try {
            localStorage.setItem(chave + '.periodo', JSON.stringify({inicio: inicio.value, fim: fim.value}));
        } catch {}
    });
    busca.addEventListener('input', () => {
        try {
            localStorage.setItem(chave + '.busca', busca.value);
        } catch {}
        aplicarBusca();
    });
    document.getElementById('limpar-historico').addEventListener('click', () => {
        try {
            localStorage.removeItem(chave + '.periodo');
            localStorage.removeItem(chave + '.busca');
        } catch {}
    });
    aplicarBusca();
})();
