
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="<?=NAME_APP?>" name="description">
		<meta content="Lucas Alcântara" name="author">

        <!-- Title -->
		<title><?=NAME_APP?></title>

		<!--Favicon -->
		<link rel="icon" href="/favicon.png" type="image/x-icon"/>

		<!--Bootstrap css -->
		<link id="style" href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<!-- Style css -->
		<link href="/assets/css/style.css" rel="stylesheet" />
		<link href="/assets/css/dark.css" rel="stylesheet" />
		<link href="/assets/css/skin-modes.css" rel="stylesheet" />

		<!-- Animate css -->
		<link href="/assets/css/animated.css" rel="stylesheet" />

		<!-- P-scroll bar css-->
		<link href="/assets/plugins/p-scrollbar/p-scrollbar.css" rel="stylesheet" />

		<!---Icons css-->
		<link href="/assets/css/icons.css" rel="stylesheet" />

	    <!-- Color Skin css -->
		<link id="theme" href="/assets/colors/color1.css" rel="stylesheet" type="text/css"/>

        <!-- toast -->
        <link href="/assets/plugins/toast/css/jquery.growl.css" rel="stylesheet" type="text/css" />

        <!-- select2 -->
        <link href="/assets/plugins/select2/select2.min.css" rel="stylesheet">

        <style>
            :root{
                --cor-logo-vermelho: #F75C5C;
                --cor-logo-cinza: #C6C6C6;
                --cor-logo-amarelo: #FBC24B;
                --cor-logo-preto: #2B2B2B;
            }
            .logo {
                text-align: left;
            }

            .dots {
                display: flex;
                justify-content: left;
                gap: 10px;
                margin-bottom: 10px;
            }

            .dot {
                width: 14px;
                height: 14px;
                border-radius: 50%;
                display: inline-block;
            }

            .red { background-color:var(--cor-logo-vermelho); }
            .gray { background-color:var(--cor-logo-cinza); }
            .yellow { background-color: var(--cor-logo-amarelo); }

            .mov {
                letter-spacing: 3px;
                font-size: 12px;
                font-weight: 400;
                color: #444;
            }

            .carvalho {
                font-size: 14px;
                letter-spacing: 6px;
                font-weight: 600;
                color: #2B2B2B;
                margin-top: 5px;
            }
        </style>
        <?= $this->renderSection('css') ?>

	</head>

	<body class="app sidebar-mini">

		<!-- Page -->
		<div class="page">
			<div class="page-main">

				<!--aside open-->
				<aside class="app-sidebar">
					<div class="app-sidebar__logo" >
                        <div class="logo">
                            <div class="dots">
                                <span class="dot red"></span>
                                <span class="dot gray"></span>
                                <span class="dot yellow"></span>
                            </div>
                            <div class="mov">MÓVEIS</div>
                            <div class="carvalho">CARVALHO</div>
                        </div>
					</div>

					<ul class="side-menu app-sidebar3" style="margin-top: 110px !important;">
						<li class="side-item side-item-category">Acesso Restrito</li>
						<li class="slide" >
							<a class="side-menu__item"  href="<?=url_to('restrito.dashboard.index')?>">
                            <i class="fa fa-tachometer" ></i>
							<span class="side-menu__label px-4 py-2">Dashboard</span></a>
						</li>

                        <li class="slide">
							<a class="side-menu__item"  href="<?=url_to('restrito.produto.index')?>">
                            <i class="fa fa-cubes"></i>
							<span class="side-menu__label px-4 py-2">Produtos</span></a>
						</li>

                        <li class="slide">
							<a class="side-menu__item"  href="<?=url_to('restrito.entrada.index')?>">
                            <i class="fa fa-sign-in"></i>
							<span class="side-menu__label px-4 py-2">Entradas</span></a>
						</li>

                        <li class="slide">
							<a class="side-menu__item"  href="<?=url_to('restrito.saida.index')?>">
                            <i class="fa fa-sign-out"></i>
							<span class="side-menu__label px-4 py-2">Saídas</span></a>
						</li>

                        <?php
                            if(session('usuario_logado')['cargo'] == 'Administrador'):
                        ?>
                        <li class="slide">
							<a class="side-menu__item"  href="<?=url_to('restrito.relatorio.index')?>">
                            <i class="fa fa-bar-chart"></i>
							<span class="side-menu__label px-4 py-2">Relatórios</span></a>
						</li>

                        <li class="slide">
							<a class="side-menu__item"  href="<?=url_to('restrito.config.index')?>">
                            <i class="fa fa-cogs"></i>
							<span class="side-menu__label px-4 py-2">Configurações</span></a>
						</li>
                        <?php
                          endif;
                        ?>
					</ul>
				</aside>
				<!--aside closed-->

				<!-- App-Content -->
				<div class="app-content main-content">
					<div class="side-app">
                        <!--app header-->
                        <div class="app-header header main-header1">
                            <div class="container-fluid">
                                <div class="d-flex">
                                    <div class="logo mt-2" style=" width: 100%; text-align: center;">
                                        <div class="dots" style="display: flex; text-align: center; justify-content: center;">
                                            <span class="dot red"></span>
                                            <span class="dot gray"></span>
                                            <span class="dot yellow"></span>
                                        </div>
                                        <div class="mov">MÓVEIS</div>
                                        <div class="carvalho">CARVALHO</div>
                                    </div>

                                    
                                    <div class="app-sidebar__toggle d-flex" data-bs-toggle="sidebar">
                                        <a class="open-toggle" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="feather feather-align-left header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
                                        </a>
                                    </div>
                                    
                                    <div class="d-flex order-lg-2 ms-auto main-header-end">
                                        <button  class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="true" aria-label="Toggle navigation">
                                            <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
                                        </button>
                                        <div class="navbar navbar-expand-lg navbar-collapse responsive-navbar p-0">
                                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                                <div class="d-flex order-lg-2">
                                                    <div class="dropdown   header-fullscreen d-flex" >
                                                        <a  class="nav-link icon full-screen-link p-0"  id="fullscreen-button">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg>
                                                        </a>
                                                    </div>
                                                    <div class="dropdown profile-dropdown d-flex">
                                                        <a href="javascript:void(0);" class="nav-link pe-0 leading-none" data-bs-toggle="dropdown">
                                                            <span class="header-avatar1">
                                                                <img src="/assets/images/users/2.jpg" alt="img" class="avatar avatar-md brround">
                                                            </span>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
                                                            <div class="text-center">
                                                                <div class="text-center user pb-0 font-weight-bold"><?=session('usuario_logado')['nome']?></div>
                                                                <span class="text-center user-semi-title"><?=session('usuario_logado')['cargo']?></span>
                                                                <div class="dropdown-divider"></div>
                                                            </div>
                                                            <a class="dropdown-item d-flex" href="<?=url_to('restrito.resetar-senha')?>">
                                                                <i class="fa fa-lock header-icon me-2"></i>
                                                                <div class="fs-13">Alterar Senha</div>
                                                            </a> 
                                                            <a class="dropdown-item d-flex" href="<?=url_to('sair')?>">
                                                                <svg class="header-icon me-2" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z"/></g></svg>
                                                                <div class="fs-13">Sair</div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/app header-->

                        <?= $this->renderSection('conteudo') ?>
					</div>
				</div><!-- right app-content-->
			</div>

			<!--Footer-->
			<footer class="footer">
				<div class="container">
					<div class="row align-items-center flex-row-reverse">
						<div class="col-md-12 col-sm-12 text-center">
							<?=NAME_APP?> - <?=date('Y')?> 
						</div>
					</div>
				</div>
			</footer>
			<!-- End Footer-->

		</div>
		<!-- End Page -->

		<!-- Back to top -->
		<a href="#top" id="back-to-top"><i class="fe fe-chevron-up"></i></a>

		<!-- Jquery js-->
		<script src="/assets/js/jquery.min.js"></script>

		<!-- Bootstrap5 js-->
		<script src="/assets/plugins/bootstrap/popper.min.js"></script>
		<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!--Othercharts js-->
		<script src="/assets/plugins/othercharts/jquery.sparkline.min.js"></script>

		<!-- Circle-progress js-->
		<script src="/assets/js/circle-progress.min.js"></script>

		<!-- Jquery-rating js-->
		<script src="/assets/plugins/rating/jquery.rating-stars.js"></script>

		<!--Sidemenu js-->
		<script src="/assets/plugins/sidemenu/sidemenu.js"></script>

		<!-- P-scroll js-->
		<script src="/assets/plugins/p-scrollbar/p-scrollbar.js"></script>
		<script src="/assets/plugins/p-scrollbar/p-scroll1.js"></script>
		<!-- <script src="/assets/plugins/p-scrollbar/p-scroll.js"></script> -->

		<!-- Custom js-->
		<script src="/assets/js/custom.js"></script>

        <!-- toast -->
        <script src="/assets/plugins/toast/js/jquery.growl.js" type="text/javascript"></script>

        <!-- sweetalert2 -->
        <script src="/assets/plugins/sweetalert/js/sweetalert2.all.min.js"></script>
        
        <!-- inputmask -->
        <script src="/assets/plugins/inputmask/jquery.inputmask.min.js" type="text/javascript"></script>

        <!-- datatables -->
        <script src="/assets/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />

        <!-- select2 -->
        <script src="/assets/plugins/select2/select2.full.min.js"></script>

        <script>
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