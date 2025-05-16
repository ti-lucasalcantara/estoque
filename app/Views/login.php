<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/assets/imagens/logo/logo.png">
   
    <!-- Bootstrap  v5.3.3 -->
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    
    <!-- sweetalert2 -->
    <link href="/assets/plugins/sweetalert/css/sweetalert2.min.css" rel="stylesheet"> 

    <!---Icons css-->
    <link href="/assets/css/icons.css" rel="stylesheet" />

    <!-- toast -->
    <link href="/assets/plugins/toast/css/jquery.growl.css" rel="stylesheet" type="text/css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root{
            --cor-logo-vermelho: #F75C5C;
            --cor-logo-cinza: #C6C6C6;
            --cor-logo-amarelo: #FBC24B;
            --cor-logo-preto: #2B2B2B;
            --backgroud:#C6C6C6;
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
        
        .login-box {
            height: 70vh;
            display: flex;
            justify-content: center;
        }
        .login-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
    
    <!-- CSS -->
    <?= $this->renderSection('css') ?>

    <title>Login</title>
</head>

<?php
$background_env = env('CI_ENVIRONMENT') == 'testing' ? 'background: url(/assets/imagens/env/hom.png);' : (env('CI_ENVIRONMENT') == 'development' ? 'background: url(/assets/imagens/env/dev.png);' : '');
?>

<body style="background-color: var(--backgroud); display: flex; flex-direction: column; min-height: 100vh; ">

    <header class="container-fluid py-3 border-bottom bg-white">
        <div class="container">
            <div class="d-flex" style="justify-content: center;">
                <div class="logo">
                    <div class="dots ">
                        <span class="dot red"></span>
                        <span class="dot gray"></span>
                        <span class="dot yellow"></span>
                    </div>
                    <div class="mov">MÓVEIS</div>
                    <div class="carvalho">CARVALHO</div>
                </div>
            </div>
        </div>
    </header>
    
    <main style="flex: 1;">
        <div class="container login-box mt-3">
            <div class="row justify-content-center w-100">
                <div class="col-12 col-md-4 col-lg-4 login-content">
                    <h5 class="text-center">Acesso Restrito</h5>
                    <form id="form" method="POST" action="<?=url_to('login')?>" class="mt-4" autocomplete="off" >
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-solid fa-user"></i></span>
                        <div class="form-floating flex-grow-1">
                            <input type="text" name="email" class="form-control <?=empty(validation_show_error('email')) ? '' : 'is-invalid'?>" id="email" placeholder="E-mail" value="<?=set_value('email', 'ti.lucasalcantara@gmail.com')?>">
                            <label for="email">E-mail</label>
                        </div>
                        <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('email') ?></small>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i id="iconToggle" class="fa fa-solid fa-eye"></i>
                        </span>
                        <div class="form-floating flex-grow-1">
                            <input type="password" name="senha" class="form-control <?= empty(validation_show_error('senha')) ? '' : 'is-invalid' ?>" id="senha" placeholder="Senha" value="<?= set_value('senha', '159357**') ?>">
                            <label for="senha">Senha</label>
                        </div>
                        <small class="text-danger pull-right w-100" style="text-align:right"><?= validation_show_error('senha') ?></small>
                    </div>
                
                    <div class="d-grid gap-2">
                        <button id="btn-send" type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Entrar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Jquery js-->
    <script src="/assets/js/jquery.min.js"></script>

    <!-- Bootstrap  v5.3.3 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- toast -->
    <script src="/assets/plugins/toast/js/jquery.growl.js" type="text/javascript"></script>

    <!-- sweetalert2 -->
    <script src="/assets/plugins/sweetalert/js/sweetalert2.all.min.js"></script>

        <script>
            function showToast(title = 'Atenção!', text = '-', type = 'default') {
            
            if (type === 'danger') type = 'error';
            if (type === 'success') type = 'notice';

            $.growl({
                title: title,
                message: text,
                style: type,
            });
        }

        </script>

    <?= $this->include('_componentes/toast') ?>
    <?= $this->include('_componentes/sweet-alert') ?>

    <script>

        
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('senha');
            const toggleIcon = document.getElementById('iconToggle');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });

        $(function(){
            $("#form").submit(function(){
                $("#btn-send").prop('disabled',true).html("<i class='fa fas fa-spinner fa-spin'></i> Aguarde");
            });
        });
    </script>

</body>
</html>