(() => {
    function mostrarCor(opcao) {
        const hex = opcao.element?.dataset.cor;
        if (!hex || !/^#(?:[a-f\d]{3}|[a-f\d]{6})$/i.test(hex)) return opcao.text;
        const rotulo = document.createElement('span');
        const amostra = document.createElement('span');
        amostra.className = 'color-swatch';
        amostra.style.backgroundColor = hex;
        amostra.setAttribute('aria-hidden', 'true');
        rotulo.append(amostra, opcao.text.trim());
        return $(rotulo);
    }

    $('#id_categoria').select2({width: '100%', placeholder: 'Selecione uma categoria'});
    const campoCor = $('#id_cor');
    const variasCores = campoCor.prop('multiple');
    campoCor.select2({
        width: '100%',
        placeholder: variasCores ? 'Selecione uma ou mais cores' : 'Selecione uma cor',
        closeOnSelect: !variasCores,
        templateResult: mostrarCor,
        templateSelection: mostrarCor
    });
    if (variasCores) {
        const resumo = document.getElementById('quantidade-variacoes');
        function atualizarVariacoes() {
            const quantidade = (campoCor.val() ?? []).length;
            resumo.textContent = quantidade === 0 ? ''
                : quantidade === 1 ? 'Será criado 1 produto.' : 'Serão criados ' + quantidade + ' produtos, um por cor.';
        }
        campoCor.on('change', atualizarVariacoes);
        atualizarVariacoes();
    }
    $('#formCadastroProduto').on('select2:open', '#id_categoria, #id_cor, [data-select-caracteristica]', () => {
        requestAnimationFrame(() => {
            document.querySelector('.select2-container--open .select2-search__field')?.focus();
        });
    });

    const linhas = document.getElementById('caracteristicas-selecionadas');
    if (linhas) {
        const modelo = document.getElementById('modelo-caracteristica');
        const adicionar = document.getElementById('adicionar-caracteristica');
        const valores = JSON.parse(document.getElementById('valores-caracteristicas').textContent);
        document.getElementById('formCadastroProduto').append(Object.assign(document.createElement('input'), {
            type: 'hidden', name: 'editar_caracteristicas', value: '1'
        }));

        function atualizarOpcoes() {
            const seletores = [...linhas.querySelectorAll('select')];
            const escolhidas = new Set(seletores.map(seletor => seletor.value).filter(Boolean));
            for (const seletor of seletores) {
                for (const opcao of seletor.options) {
                    opcao.disabled = opcao.value !== seletor.value && escolhidas.has(opcao.value);
                }
            }
            const ultima = seletores[seletores.length - 1];
            adicionar.disabled = Boolean(ultima && (!ultima.value || escolhidas.size >= ultima.options.length - 1));
        }

        function adicionarLinha(id = '', valor = '') {
            const linha = modelo.content.firstElementChild.cloneNode(true);
            const seletor = linha.querySelector('select');
            const campo = linha.querySelector('input');
            seletor.value = id;
            campo.value = valor;
            function vincularValor() {
                campo.name = seletor.value ? 'caracteristicas[' + seletor.value + ']' : '';
                atualizarOpcoes();
            }
            linha.querySelector('button').addEventListener('click', () => {
                $(seletor).select2('destroy');
                linha.remove();
                atualizarOpcoes();
                if (!adicionar.disabled) adicionar.focus();
            });
            linhas.append(linha);
            $(seletor).select2({width: '100%', placeholder: 'Selecione uma característica'}).on('change', vincularValor);
            vincularValor();
            return seletor;
        }
        for (const [id, valor] of Object.entries(valores)) adicionarLinha(id, valor);
        adicionar.addEventListener('click', () => $(adicionarLinha()).select2('open'));
    }

    const galeria = document.getElementById('galeria-fotos');
    const seletorFotos = document.getElementById('selecionar-fotos');
    const areaFotos = document.getElementById('area-fotos');
    const adicionar = document.getElementById('adicionar-foto');
    const mensagem = document.getElementById('erro-fotos');
    const preview = document.getElementById('preview-foto');
    const moldura = document.getElementById('moldura-foto');
    const imagem = document.getElementById('imagem-preview');
    const tituloFoto = document.getElementById('titulo-foto');
    const indicadorCapa = document.getElementById('indicador-capa');
    const definirCapa = document.getElementById('definir-capa');
    const removerFoto = document.getElementById('remover-foto');
    const controles = preview.querySelectorAll('[data-ajuste]');
    const fotos = [];
    const removidas = new Set();
    const entradas = document.getElementById('fotos-envio');
    const estado = document.getElementById('galeria-envio');
    let capa;
    let selecionada;
    let arraste;

    function sincronizar() {
        const arquivos = new DataTransfer();
        const dados = {existentes: {}, novas: {}, removidas: [...removidas]};
        for (const foto of fotos) {
            const id = foto.arquivo ? arquivos.items.length : foto.id;
            if (foto.arquivo) arquivos.items.add(foto.arquivo);
            dados[foto.arquivo ? 'novas' : 'existentes'][id] = foto.ajustes;
            if (foto === capa) dados.capa = {tipo: foto.arquivo ? 'nova' : 'existente', id};
        }
        entradas.files = arquivos.files;
        estado.value = JSON.stringify(dados);
    }

    function enquadrar() {
        const {x, y, zoom} = selecionada.ajustes;
        imagem.style.objectPosition = x + '% ' + y + '%';
        imagem.style.transformOrigin = imagem.style.objectPosition;
        imagem.style.transform = 'scale(' + (zoom / 100) + ')';
    }

    function mostrarFoto(foto) {
        if (arraste) encerrarArraste(arraste.id);
        selecionada = foto;
        preview.hidden = !foto;
        if (foto) {
            if (imagem.getAttribute('src') !== foto.url) imagem.src = foto.url;
            imagem.alt = foto.titulo;
            tituloFoto.textContent = foto.titulo;
            indicadorCapa.hidden = foto !== capa;
            definirCapa.disabled = foto === capa;
            for (const controle of controles) controle.value = foto.ajustes[controle.dataset.ajuste];
            enquadrar();
        } else {
            imagem.removeAttribute('src');
        }
        for (const item of fotos) {
            item.miniatura.setAttribute('aria-pressed', String(item === foto));
            item.miniatura.setAttribute('aria-label', item.titulo + (item === capa ? ' (capa)' : ''));
        }
    }

    function adicionarFoto(foto) {
        foto.titulo = foto.arquivo?.name ?? 'Foto cadastrada ' + foto.id;
        const botao = document.createElement('button');
        botao.type = 'button';
        botao.className = 'photo-thumbnail';
        botao.title = foto.titulo;
        const miniatura = document.createElement('img');
        miniatura.src = foto.url;
        miniatura.alt = '';
        botao.append(miniatura);
        botao.addEventListener('click', () => mostrarFoto(foto));
        foto.miniatura = botao;
        fotos.push(foto);
        galeria.append(botao);
    }

    for (const controle of controles) {
        controle.addEventListener('input', () => {
            if (!selecionada) return;
            selecionada.ajustes[controle.dataset.ajuste] = controle.valueAsNumber;
            enquadrar();
        });
    }

    function encerrarArraste(id) {
        if (arraste?.id !== id) return;
        arraste = null;
        moldura.classList.remove('is-panning');
        if (moldura.hasPointerCapture(id)) moldura.releasePointerCapture(id);
    }

    moldura.addEventListener('pointerdown', evento => {
        if (arraste || !selecionada || !imagem.complete || !imagem.naturalWidth || evento.button !== 0) return;
        const {width, height} = moldura.getBoundingClientRect();
        const {naturalWidth, naturalHeight} = imagem;
        const {x, y, zoom} = selecionada.ajustes;
        const escala = Math.max(width / naturalWidth, height / naturalHeight) * zoom / 100;
        const alcanceX = naturalWidth * escala - width;
        const alcanceY = naturalHeight * escala - height;
        if (alcanceX < 1 && alcanceY < 1) return;
        arraste = {id: evento.pointerId, origemX: evento.clientX, origemY: evento.clientY, x, y, alcanceX, alcanceY};
        moldura.setPointerCapture(evento.pointerId);
        moldura.classList.add('is-panning');
        evento.preventDefault();
    });

    moldura.addEventListener('pointermove', evento => {
        if (arraste?.id !== evento.pointerId) return;
        const posicionar = (inicio, delta, alcance) => alcance < 1
            ? inicio : Math.round(Math.max(0, Math.min(100, inicio - delta * 100 / alcance)));
        selecionada.ajustes.x = posicionar(arraste.x, evento.clientX - arraste.origemX, arraste.alcanceX);
        selecionada.ajustes.y = posicionar(arraste.y, evento.clientY - arraste.origemY, arraste.alcanceY);
        for (const controle of controles) controle.value = selecionada.ajustes[controle.dataset.ajuste];
        enquadrar();
    });
    for (const tipo of ['pointerup', 'pointercancel', 'lostpointercapture']) {
        moldura.addEventListener(tipo, evento => encerrarArraste(evento.pointerId));
    }

    definirCapa.addEventListener('click', () => {
        capa = selecionada;
        mostrarFoto(selecionada);
    });
    removerFoto.addEventListener('click', () => {
        const foto = selecionada;
        if (!foto) return;
        const indice = fotos.indexOf(foto);
        if (foto.arquivo) URL.revokeObjectURL(foto.url);
        else removidas.add(foto.id);
        fotos.splice(indice, 1);
        foto.miniatura.remove();
        if (capa === foto) capa = fotos[0];
        mostrarFoto(fotos[indice] ?? fotos[indice - 1]);
        (selecionada?.miniatura ?? adicionar).focus();
    });

    for (const foto of JSON.parse(document.getElementById('fotos-iniciais').textContent)) {
        adicionarFoto(foto);
        if (foto.capa) capa = foto;
    }
    capa ||= fotos[0];
    mostrarFoto(capa);

    function receberFotos(arquivos) {
        const invalidas = [];
        const inicio = fotos.length;
        for (const arquivo of arquivos) {
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(arquivo.type) || arquivo.size > 8 * 1024 * 1024) {
                invalidas.push(arquivo.name);
                continue;
            }
            adicionarFoto({arquivo, url: URL.createObjectURL(arquivo), ajustes: {zoom: 100, x: 50, y: 50}});
        }
        capa ||= fotos[0];
        mostrarFoto(fotos[inicio] ?? selecionada);
        mensagem.hidden = !invalidas.length;
        mensagem.textContent = invalidas.length ? 'Use JPG, PNG ou WebP de até 8 MB. Arquivos ignorados: ' + invalidas.join(', ') : '';
    }

    areaFotos.addEventListener('click', () => seletorFotos.click());
    adicionar.addEventListener('click', () => seletorFotos.click());
    seletorFotos.addEventListener('change', () => {
        receberFotos(seletorFotos.files);
        seletorFotos.value = '';
    });
    areaFotos.addEventListener('dragover', evento => {
        evento.preventDefault();
        evento.dataTransfer.dropEffect = 'copy';
        areaFotos.classList.add('is-dragging');
    });
    areaFotos.addEventListener('dragleave', evento => {
        if (!areaFotos.contains(evento.relatedTarget)) areaFotos.classList.remove('is-dragging');
    });
    areaFotos.addEventListener('drop', evento => {
        evento.preventDefault();
        areaFotos.classList.remove('is-dragging');
        receberFotos(evento.dataTransfer.files);
    });

    document.getElementById('formCadastroProduto').addEventListener('submit', sincronizar);
})();
