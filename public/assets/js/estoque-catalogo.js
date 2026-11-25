(() => {
    const grade = document.getElementById('cards-view');
    const filtros = document.getElementById('filtros-catalogo');
    const busca = filtros.elements.busca;
    const saldoDe = filtros.elements.saldo_de;
    const saldoAte = filtros.elements.saldo_ate;
    const limpar = document.getElementById('limpar-filtros');
    const normalizar = valor => valor.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
    const itens = [...grade.querySelectorAll('[data-search]')].map(card => ({
        card,
        texto: normalizar(card.dataset.search),
        saldo: Number(card.dataset.saldo),
    }));
    const chaveBusca = 'estoque.produtos.busca';
    const chaveFiltros = 'estoque.produtos.filtros';
    const camposSelecao = ['categoria', 'cor', 'situacao'];
    const camposAvancados = [...camposSelecao, 'saldo_de', 'saldo_ate'];
    try {
        busca.value = localStorage.getItem(chaveBusca) ?? '';
        const salvos = JSON.parse(localStorage.getItem(chaveFiltros) ?? '{}');
        for (const campo of camposAvancados) {
            const valor = salvos?.[campo];
            if (typeof valor !== 'string') continue;
            const controle = filtros.elements[campo];
            controle.value = valor;
            if (controle.selectedIndex === -1) controle.value = '';
        }
    } catch {}

    function filtrar() {
        const termos = normalizar(busca.value).split(/\s+/).filter(Boolean);
        const selecoes = camposSelecao.map(campo => [campo, filtros.elements[campo].value]);
        const minimo = Number.isFinite(saldoDe.valueAsNumber) ? saldoDe.valueAsNumber : -Infinity;
        const maximo = Number.isFinite(saldoAte.valueAsNumber) ? saldoAte.valueAsNumber : Infinity;
        const faixaValida = minimo <= maximo;
        document.getElementById('erro-faixa-saldo').hidden = faixaValida;
        for (const campo of [saldoDe, saldoAte]) campo.setAttribute('aria-invalid', String(!faixaValida));

        let total = 0;
        for (const {card, texto, saldo} of itens) {
            card.hidden = !termos.every(termo => texto.includes(termo))
                || !selecoes.every(([campo, valor]) => !valor || card.dataset[campo] === valor)
                || (faixaValida && (saldo < minimo || saldo > maximo));
            if (!card.hidden) total++;
        }
        document.getElementById('catalog-count').textContent = total + (total === 1 ? ' produto' : ' produtos');
        document.getElementById('catalog-empty').hidden = total > 0;
        const ativos = selecoes.filter(([, valor]) => valor).length + Number(saldoDe.value !== '') + Number(saldoAte.value !== '');
        const indicador = document.getElementById('filtros-ativos');
        indicador.hidden = ativos === 0;
        indicador.textContent = ativos + (ativos === 1 ? ' ativo' : ' ativos');
        limpar.hidden = ativos === 0 && !busca.value;
    }
    filtros.addEventListener('submit', evento => evento.preventDefault());
    filtros.addEventListener('input', evento => {
        try {
            if (evento.target === busca) {
                localStorage.setItem(chaveBusca, busca.value);
            } else if (camposAvancados.includes(evento.target.name)) {
                const valores = Object.fromEntries(camposAvancados.map(campo => [campo, filtros.elements[campo].value]));
                localStorage.setItem(chaveFiltros, JSON.stringify(valores));
            }
        } catch {}
        filtrar();
    });
    filtros.addEventListener('reset', () => {
        try {
            localStorage.removeItem(chaveBusca);
            localStorage.removeItem(chaveFiltros);
        } catch {}
        requestAnimationFrame(() => {
            filtrar();
            busca.focus();
        });
    });
    filtrar();

    let gravando = false;

    for (const elemento of grade.querySelectorAll('[data-galeria-arrastavel]')) {
        const carrossel = new bootstrap.Carousel(elemento, {interval: false});
        const moldura = elemento.querySelector('.carousel-inner');
        const card = elemento.closest('[data-produto]');
        const barra = card.querySelector('[data-capa-atual]');
        const contador = barra.querySelector('[data-contador-fotos]');
        const indicador = barra.querySelector('[data-indicador-capa]');
        const botao = barra.querySelector('[data-usar-capa]');
        const erro = barra.querySelector('[data-erro-capa]');
        const fotos = [...elemento.querySelectorAll('[data-foto]')];
        let arraste;
        let transicao = false;

        function atualizar() {
            const ativa = elemento.querySelector('.carousel-item.active');
            contador.textContent = (fotos.indexOf(ativa) + 1) + ' de ' + fotos.length;
            if (botao) {
                const ehCapa = ativa.dataset.foto === barra.dataset.capaAtual;
                indicador.hidden = !ehCapa;
                botao.hidden = ehCapa;
                botao.disabled = transicao || gravando;
            }
        }

        elemento.addEventListener('slide.bs.carousel', () => {
            transicao = true;
            atualizar();
        });
        elemento.addEventListener('slid.bs.carousel', () => {
            transicao = false;
            atualizar();
        });

        function encerrarArraste(evento) {
            if (arraste?.id !== evento.pointerId) return;
            const distancia = evento.clientX - arraste.x;
            arraste = null;
            moldura.classList.remove('is-dragging');
            if (moldura.hasPointerCapture(evento.pointerId)) moldura.releasePointerCapture(evento.pointerId);
            if (evento.type === 'pointerup' && Math.abs(distancia) >= 35) {
                if (distancia < 0) carrossel.next();
                else carrossel.prev();
            }
        }

        // O Bootstrap já trata o gesto de deslizar com o toque.
        moldura.addEventListener('pointerdown', evento => {
            if (evento.pointerType !== 'mouse' || evento.button !== 0 || transicao) return;
            arraste = {id: evento.pointerId, x: evento.clientX};
            moldura.setPointerCapture(evento.pointerId);
            moldura.classList.add('is-dragging');
            evento.preventDefault();
        });
        for (const tipo of ['pointerup', 'pointercancel', 'lostpointercapture']) {
            moldura.addEventListener(tipo, encerrarArraste);
        }

        botao?.addEventListener('click', async () => {
            if (gravando || transicao) return;
            const foto = elemento.querySelector('.carousel-item.active').dataset.foto;
            gravando = true;
            atualizar();
            botao.textContent = 'Salvando…';
            erro.hidden = true;
            const dados = new FormData();
            dados.append('produto', card.dataset.produto);
            dados.append('foto', foto);
            dados.append(grade.dataset.tokenNome, grade.dataset.tokenValor);
            try {
                const resposta = await fetch(grade.dataset.urlCapa, {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest'},
                    body: dados
                });
                const resultado = await resposta.json();
                if (resultado.token) grade.dataset.tokenValor = resultado.token;
                if (!resposta.ok) throw new Error(resultado.text ?? '');
                barra.dataset.capaAtual = foto;
            } catch (falha) {
                erro.textContent = falha instanceof SyntaxError || !falha.message
                    ? 'Não foi possível salvar a capa. Atualize a página e tente de novo.' : falha.message;
                erro.hidden = false;
            } finally {
                gravando = false;
                botao.textContent = 'Usar como capa';
                atualizar();
            }
        });
    }
})();
