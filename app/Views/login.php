<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar | Móveis Carvalho</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/icons.css">
    <link rel="stylesheet" href="/assets/plugins/toast/css/jquery.growl.css">
    <link rel="stylesheet" href="/assets/css/estoque.css">
</head>
<body class="estoque-login">
    <main class="login-layout">
        <header class="login-brand-panel">
            <div class="brand">
                <span class="brand-symbol" aria-hidden="true"><i class="fa fa-cubes"></i></span>
                <strong>Móveis Carvalho</strong>
            </div>
            <h1>Gestão de estoque</h1>
        </header>
        <section class="login-form-panel" aria-labelledby="titulo-login">
            <div class="card login-card">
                <h2 id="titulo-login">Entrar</h2>
                <p class="text-muted mb-4">Use seu e-mail e sua senha de acesso.</p>
                <form method="post" action="<?= url_to('login') ?>" id="form-login">
                    <?php $erroEmail = validation_show_error('email'); ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" value="<?= set_value('email') ?>" class="form-control <?= $erroEmail ? 'is-invalid' : '' ?>" autocomplete="username" aria-describedby="erro-email" required>
                        <small class="text-danger" id="erro-email"><?= $erroEmail ?></small>
                    </div>
                    <?php $erroSenha = validation_show_error('senha'); ?>
                    <div class="mb-4">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group">
                            <input type="password" name="senha" id="senha" class="form-control <?= $erroSenha ? 'is-invalid' : '' ?>" autocomplete="current-password" aria-describedby="erro-senha" required>
                            <button class="btn btn-outline-primary" type="button" id="mostrar-senha" aria-pressed="false">Mostrar</button>
                        </div>
                        <small class="text-danger" id="erro-senha"><?= $erroSenha ?></small>
                    </div>
                    <button class="btn btn-primary w-100" type="submit" id="entrar">Entrar</button>
                </form>
            </div>
        </section>
    </main>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/plugins/toast/js/jquery.growl.js"></script>
    <script src="/assets/plugins/sweetalert/js/sweetalert2.all.min.js"></script>
    <script>
        function showToast(title, text, type) {
            $.growl({title, message: text, style: {danger: 'error', success: 'notice'}[type] || type});
        }
        const mostrarSenha = document.getElementById('mostrar-senha');
        mostrarSenha.addEventListener('click', () => {
            const senha = document.getElementById('senha');
            const visivel = senha.type === 'password';
            senha.type = visivel ? 'text' : 'password';
            mostrarSenha.textContent = visivel ? 'Ocultar' : 'Mostrar';
            mostrarSenha.setAttribute('aria-pressed', String(visivel));
        });
        document.getElementById('form-login').addEventListener('submit', () => {
            const entrar = document.getElementById('entrar');
            entrar.disabled = true;
            entrar.textContent = 'Entrando…';
        });
        window.addEventListener('pageshow', () => {
            const entrar = document.getElementById('entrar');
            entrar.disabled = false;
            entrar.textContent = 'Entrar';
        });
    </script>
    <?= $this->include('_componentes/toast') ?>
    <?= $this->include('_componentes/sweet-alert') ?>
</body>
</html>