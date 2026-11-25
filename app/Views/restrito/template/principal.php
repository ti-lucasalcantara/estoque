<?php
$usuario = session('usuario_logado') ?? [];
$pode = static fn (string $permissao): bool => \App\Libraries\PermissoesEstoque::tem($permissao);
$caminho = trim(service('uri')->getRoutePath(), '/');
$secoes = [
    ['Visão geral', 'th-large', 'restrito.dashboard.index', 'restrito', $pode('dashboard.ver')],
    ['Produtos', 'cubes', $pode('produto.ver') ? 'restrito.produto.index' : 'restrito.produto.formulario', 'restrito/produto', $pode('produto.ver') || $pode('produto.criar')],
    ['Entradas', 'arrow-down', $pode('entrada.ver') ? 'restrito.entrada.index' : 'restrito.entrada.formularioMultiplas', 'restrito/produto/in', $pode('entrada.ver') || $pode('entrada.criar')],
    ['Saídas', 'arrow-up', $pode('saida.ver') ? 'restrito.saida.index' : 'restrito.saida.formularioMultiplas', 'restrito/produto/out', $pode('saida.ver') || $pode('saida.criar')],
    ['Relatórios', 'bar-chart', 'restrito.relatorio.index', 'restrito/relatorios', $pode('relatorio.ver')],
    ['Configurações', 'cog', 'restrito.config.index', 'restrito/config', \App\Libraries\PermissoesEstoque::podeAbrirConfiguracoes()],
];
$secoes = array_filter($secoes, static fn ($secao) => $secao[4]);
$secaoAtual = 'Minha conta';
foreach ($secoes as [$rotulo, $icone, $rota, $prefixo]) {
    if ($caminho === $prefixo || ($prefixo !== 'restrito' && str_starts_with($caminho, $prefixo . '/'))) {
        $secaoAtual = $rotulo;
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#faf7f4">
    <title><?= esc($secaoAtual) ?> | <?= esc(NAME_APP) ?></title>
    <link rel="icon" href="/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/icons.css">
    <link rel="stylesheet" href="/assets/plugins/toast/css/jquery.growl.css">
    <link rel="stylesheet" href="/assets/plugins/datatables/datatables.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2/select2.min.css">
    <?= $this->renderSection('css') ?>
    <link rel="stylesheet" href="/assets/css/estoque.css?v=<?= filemtime(FCPATH . 'assets/css/estoque.css') ?>">
</head>
<body class="estoque-app">
    <a class="skip-link" href="#conteudo">Ir para o conteúdo</a>
    <header class="workspace-header">
        <div class="workspace-header-inner">
            <div class="workspace-brand workspace-brand-desktop">
                <a class="brand d-none d-lg-block" href="<?= url_to('restrito.dashboard.index') ?>">
                    <?= $this->include('_componentes/marca-carvalho') ?>
                </a>
                <button class="btn btn-outline-primary workspace-menu-toggle" id="alternar-menu" type="button"
                    aria-controls="menuEstoque" aria-expanded="false" aria-label="Abrir menu lateral" title="Abrir menu lateral">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
            </div>
            <div class="workspace-location">
                <span><?= esc($secaoAtual) ?></span>
            </div>
            <div class="dropdown">
                <button type="button" class="btn account-button dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu da conta">
                    <span class="account-avatar"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <span class="account-name"><?= esc($usuario['nome'] ?? 'Minha conta') ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-header"><?= esc($usuario['cargo'] ?? '') ?></span></li>
                    <li><a class="dropdown-item" href="<?= url_to('restrito.resetar-senha') ?>">Alterar senha</a></li>
                <li>
                    <form method="post" action="<?= url_to('sair') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="dropdown-item">Sair</button>
                    </form>
                </li>
                </ul>
            </div>
        </div>
    </header>
    <aside class="offcanvas offcanvas-start workspace-menu" tabindex="-1" id="menuEstoque" aria-label="Menu lateral">
        <div class="workspace-brand d-lg-none">
            <a class="brand" href="<?= url_to('restrito.dashboard.index') ?>">
                <?= $this->include('_componentes/marca-carvalho') ?>
                <small>Gestão de estoque</small>
            </a>
            <button class="btn-close d-lg-none" type="button" data-bs-dismiss="offcanvas" aria-label="Fechar menu"></button>
        </div>
        <nav class="workspace-nav" aria-label="Menu principal">
            <?php foreach ($secoes as [$rotulo, $icone, $rota, $prefixo]): ?>
                <a href="<?= url_to($rota) ?>"<?= $secaoAtual === $rotulo ? ' aria-current="page"' : '' ?>>
                    <i class="fa fa-<?= $icone ?>" aria-hidden="true"></i>
                    <span><?= esc($rotulo) ?></span>
                </a>
            <?php endforeach ?>
        </nav>
    </aside>
    <div class="workspace">
        <main class="workspace-content" id="conteudo" tabindex="-1">
            <?= $this->renderSection('conteudo') ?>
        </main>
    </div>

    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/plugins/toast/js/jquery.growl.js"></script>
    <script src="/assets/plugins/sweetalert/js/sweetalert2.all.min.js"></script>
    <script src="/assets/plugins/datatables/datatables.min.js"></script>
    <script src="/assets/plugins/select2/select2.full.min.js"></script>

        <script>
            const menuDesktop = window.matchMedia('(min-width: 992px)');
            const alternarMenu = document.getElementById('alternar-menu');
            const painelMenu = document.getElementById('menuEstoque');
            const chaveMenu = 'estoque.menuLateralOculto';
            let menuRecolhido = false;

            function atualizarBotaoMenu() {
                const aberto = menuDesktop.matches
                    ? !menuRecolhido
                    : painelMenu.classList.contains('show');
                alternarMenu.setAttribute('aria-expanded', String(aberto));
                const rotulo = menuDesktop.matches
                    ? (aberto ? 'Recolher menu lateral' : 'Expandir menu lateral')
                    : (aberto ? 'Fechar menu lateral' : 'Abrir menu lateral');
                alternarMenu.setAttribute('aria-label', rotulo);
                alternarMenu.title = rotulo;
            }

            try {
                menuRecolhido = localStorage.getItem(chaveMenu) === 'true';
            } catch {}
            document.body.classList.toggle('workspace-menu-hidden', menuRecolhido);
            atualizarBotaoMenu();

            alternarMenu.addEventListener('click', () => {
                if (!menuDesktop.matches) {
                    bootstrap.Offcanvas.getOrCreateInstance(painelMenu).toggle(alternarMenu);
                    return;
                }

                menuRecolhido = !menuRecolhido;
                document.body.classList.toggle('workspace-menu-hidden', menuRecolhido);
                atualizarBotaoMenu();
                try {
                    localStorage.setItem(chaveMenu, String(menuRecolhido));
                } catch {}
            });

            painelMenu.addEventListener('shown.bs.offcanvas', atualizarBotaoMenu);
            painelMenu.addEventListener('hidden.bs.offcanvas', () => {
                atualizarBotaoMenu();
                if (!menuDesktop.matches) alternarMenu.focus();
            });
            function atualizarModoMenu() {
                const painel = bootstrap.Offcanvas.getInstance(painelMenu);
                if (menuDesktop.matches && painel) painel.hide();
                atualizarBotaoMenu();
            }
            if (menuDesktop.addEventListener) {
                menuDesktop.addEventListener('change', atualizarModoMenu);
            } else {
                menuDesktop.addListener(atualizarModoMenu);
            }

            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: "/assets/plugins/datatables/pt-BR.json"
                }
            });

            $.fn.loading = function(disabled=true, text='') {
                return this.each(function() {
                    const displayText = text || '<i class="fa fa-spinner fa-spin"></i> Aguarde...';
                    $(this).html(displayText).prop('disabled', disabled);
                });
            };

            $.fn.resetLoading = function(text='Ok') {
                return this.each(function() {
                    $(this).html(`${text}`).prop('disabled', false);
                });
            };

            $.fn.retorno = function(type='',title='',text='') {
                return this.each(function() {
                    $(this).empty().html(`<div class='alert alert-${type}'><b>${title}</b><br>${text}</div>`);
                });
            };

            function showToast(title = 'Atenção!', text = '-', type = 'default') {
                
                if (type === 'danger') type = 'error';
                if (type === 'success') type = 'notice';

                $.growl({
                    title: title,
                    message: text,
                    style: type,
                });
            }

            async function executarAjax(url, data, method='POST', debug=false) {
                try {
                    
                    if ( url === null || data === null ){
                        throw 'Parameter is not found!';
                    }

                    const result = await $.ajax({
                        type: method,
                        method: method,
                        url: url,
                        data: data,
                        dataType: 'json',
                        enctype:"multipart/form-data",
                        contentType: false,
                        processData: false,
                        beforeSend: function(){
                            // console.log('beforeSend');
                        },
                        success: function(res){
                            // console.log('success', res);
                        }
                    }).done(function( res ) {
                        // console.log('done',res);

                    }).fail(function(jqXHR, textStatus, errorThrown) {

                        console.error('Erro HTTP:', jqXHR.status); 
                        console.error('Retorno:', jqXHR);

                        if(jqXHR.status != 200){
                            if (jqXHR.responseJSON && jqXHR.responseJSON.show_toast === true) {
                                showToast(jqXHR.responseJSON.title, jqXHR.responseJSON.text, jqXHR.responseJSON.type);
                            }
                        }

                    }).always(function( res, textStatus, jqXHR ) {
                        if(debug){
                            console.log('always',res);
                        }
                        return res;
                    }); 
                    
                    return result;

                } catch (error) {
                    console.error('Catch error:', error);
                    if (error.responseJSON && error.responseJSON.show_toast === true) {
                        showToast(error.responseJSON.title, error.responseJSON.text, error.responseJSON.type);
                    }
                    throw error;
                    return false;
                }
            }
        </script>

        <?= $this->include('_componentes/toast') ?>
        <?= $this->include('_componentes/sweet-alert') ?>
        <?= $this->include('_componentes/modal-excluir') ?>

        <!-- JS -->
        <?= $this->renderSection('js') ?>
	</body>
</html>
