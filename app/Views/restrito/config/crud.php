<?= $this->extend('restrito/template/principal') ?>

<?= $this->section('conteudo') ?>
<?php
$campoId = getIdNameTabela($tabela);
$editando = isset($item[$campoId]);
$cargoSelecionado = old('cargo', $item['cargo'] ?? 'Usuário', false);
$permissoes = $editando ? \App\Libraries\PermissoesEstoque::selecionadas($item['permissoes'] ?? null) : [];
if (old('tabela', null, false) === 'tb_usuario') {
    $permissoes = (array) old('permissoes', [], false);
}
$rotaCadastro = url_to('restrito.config.crud', base64_encode($titulo), base64_encode($tabela));
$usuarios = $tabela === 'tb_usuario';
$registros = [];
foreach ($dados ?? [] as $linha) {
    if ($usuarios && (int) $linha[$campoId] === 1) {
        continue;
    }
    $registros[] = $linha;
}
$cadastroRecolhivel = $usuarios && !$editando;
$rotulos = ['label' => 'Nome exibido', 'caracteristica' => 'Identificador da característica', 'email' => 'E-mail', 'local' => 'Local', 'motivo_entrada' => 'Motivo de entrada', 'motivo_saida' => 'Motivo de saída', 'hexadecimal' => 'Escolha a cor'];
$rotulosTabela = $tabela === 'ref_cor' ? ['hexadecimal' => 'Amostra da cor'] + $rotulos : $rotulos;
$orientacoes = [
    'ref_categoria' => 'Use uma categoria para agrupar produtos do mesmo tipo. Exemplo: Colchão.',
    'ref_cor' => 'Informe o nome da cor, como Bege, e clique no seletor para escolher a tonalidade correspondente.',
    'ref_caracteristica' => 'Cada característica vira um campo no formulário do produto. Exemplo: use “firmeza” como identificador e “Firmeza do colchão” como nome exibido.',
    'ref_local' => 'Informe um lugar físico usado nas entradas e saídas. Exemplo: Depósito principal.',
    'ref_motivo_entrada' => 'Informe por que um produto entrou no estoque. Exemplo: Compra de fornecedor.',
    'ref_motivo_saida' => 'Informe por que um produto saiu do estoque. Exemplo: Venda.',
    'tb_usuario' => 'Informe o nome e o e-mail de quem vai usar o sistema. Escolha “Usuário” para a operação diária ou “Administrador” para quem também precisa acessar as configurações.',
];
?>

<!-- Page header -->
<div class="page-header">
    <div class="page-leftheader">
        <h1 class="page-title mb-0"><?= esc($titulo ?? '') ?></h1>
    </div>
    <div class="page-rightheader">
        <a href="<?= url_to('restrito.config.index') ?>" class="btn btn-outline-primary">
            <i class="fe fe-arrow-left me-2" aria-hidden="true"></i> Voltar às configurações
        </a>
    </div>
</div>
<!-- End Page header -->

<?php if ($podeEditar): ?>
<?php if ($cadastroRecolhivel): ?>
<details class="card user-create"<?= old('tabela', null, false) === 'tb_usuario' ? ' open' : '' ?>>
    <summary class="card-header">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        <strong>Novo usuário</strong>
        <i class="fa fa-chevron-down user-create-chevron" aria-hidden="true"></i>
    </summary>
<?php else: ?>
<section class="card" aria-labelledby="titulo-formulario">
    <div class="card-header">
        <h2 id="titulo-formulario" class="card-title mb-0"><?= $editando ? ($usuarios ? 'Editar usuário' : 'Editar registro') : 'Cadastrar novo registro' ?></h2>
    </div>
<?php endif ?>
    <div class="card-body">
        <p id="orientacao-cadastro" class="form-guidance">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <span><?= esc($orientacoes[$tabela]) ?></span>
        </p>
        <form action="<?=url_to('restrito.config.salvar')?>" method="post" aria-describedby="orientacao-cadastro">
            <?= csrf_field() ?>
            <input type="hidden" name="titulo" value="<?= $titulo?>">
            <input type="hidden" name="tabela" value="<?= $tabela?>">

            <?php if (isset($item[getIdNameTabela($tabela)])): ?>
                <input type="hidden" name="id" value="<?= $item[getIdNameTabela($tabela)] ?>">
            <?php endif; ?>

            <?php foreach ($camposVisiveis as $campo): ?>
                <?php if ( $campo == getIdNameTabela($tabela) ) continue; ?>
                <?php 
                    if ($campo === 'hexadecimal' && $tabela === 'ref_cor'){
                        $type="color";
                    }else{
                        $type="text";
                    }

                    if ($campo === 'cargo' && $tabela === 'tb_usuario'){
                        $type="select";
                    }
                ?>

                <?php
                    if ($type === 'select' && $campo === 'cargo' && $tabela === 'tb_usuario'){
                        $options = ['Administrador', 'Usuário'];
                ?>
                        <div class="mb-3">
                            <label for="<?= $campo ?>" class="form-label"><?= ucfirst($campo) ?></label>
                            <select class="form-select" id="<?= $campo ?>" name="<?= $campo ?>" required>
                                <?php foreach ($options as $option): ?>
                                    <option value="<?= esc($option) ?>" <?= $cargoSelecionado === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                <?php
                    }else{
                ?>
                <div class="mb-3">
                    <label for="<?= $campo ?>" class="form-label"><?= esc($rotulos[$campo] ?? ucfirst($campo)) ?></label>
                    <input 
                        type="<?= $type ?>" 
                        class="form-control" 
                        id="<?= $campo ?>" 
                        name="<?= $campo ?>" 
                        value="<?= esc(old($campo, $item[$campo] ?? '', false), 'attr') ?>"
                        required>

                </div>
                <?php
                    }
                ?>

            <?php endforeach; ?>

            <?php if ($usuarios && !$editando): ?>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha inicial</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="senha" name="senha" autocomplete="new-password"
                            minlength="3" maxlength="72" aria-describedby="orientacao-senha" required>
                        <button type="button" class="btn btn-outline-primary toggle-password" data-target="#senha" aria-pressed="false">Mostrar</button>
                    </div>
                    <small id="orientacao-senha" class="text-muted">Defina a senha que o usuário usará para entrar pela primeira vez.</small>
                </div>
            <?php endif; ?>

            <?php if ($tabela === 'tb_usuario'): ?>
                <fieldset class="mb-4" id="permissoes-usuario">
                    <legend class="h5">Permissões de acesso</legend>
                    <p class="text-muted small" id="orientacao-permissoes">Para o cargo Usuário, marque apenas as tarefas que a pessoa precisa executar. Administradores têm acesso completo, incluindo o gerenciamento de contas.</p>
                    <label class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="selecionar-todas-permissoes">
                        <span class="form-check-label fw-semibold">Selecionar todas as permissões</span>
                    </label>
                    <div class="hub-grid" aria-describedby="orientacao-permissoes">
                        <?php foreach (\App\Libraries\PermissoesEstoque::grupos() as $grupo => $opcoes): ?>
                            <fieldset class="card p-3 mb-0" data-grupo-permissoes>
                                <legend class="card-title"><?= esc($grupo) ?></legend>
                                <label class="form-check mb-2 pb-2 border-bottom">
                                    <input class="form-check-input" type="checkbox" data-selecionar-grupo
                                        aria-label="<?= esc('Selecionar todo o grupo ' . $grupo, 'attr') ?>">
                                    <span class="form-check-label fw-semibold">Selecionar grupo</span>
                                </label>
                                <?php foreach ($opcoes as $permissao => $rotulo): ?>
                                    <label class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="permissoes[]" value="<?= esc($permissao, 'attr') ?>"
                                            data-selecionada="<?= in_array($permissao, $permissoes, true) ? '1' : '0' ?>"
                                            <?= $cargoSelecionado === 'Administrador' || in_array($permissao, $permissoes, true) ? 'checked' : '' ?>
                                            <?= $cargoSelecionado === 'Administrador' ? 'disabled' : '' ?>>
                                        <span class="form-check-label"><?= esc($rotulo) ?></span>
                                    </label>
                                <?php endforeach ?>
                            </fieldset>
                        <?php endforeach ?>
                    </div>
                </fieldset>
            <?php endif ?>

            <div class="d-flex justify-content-end flex-wrap gap-2">
                <?php if ($editando): ?>
                    <a href="<?= $rotaCadastro ?>" class="btn btn-outline-primary">Cancelar edição</a>
                <?php endif ?>
                <button type="submit" class="btn btn-primary">
                    <?= $editando ? 'Salvar alterações' : ($usuarios ? 'Cadastrar usuário' : 'Cadastrar registro') ?>
                </button>
            </div>
        </form>
    </div>
<?php if ($cadastroRecolhivel): ?></details><?php else: ?></section><?php endif ?>
<?php endif ?>

<section class="card" aria-labelledby="titulo-registros">
    <div class="card-header">
        <h2 id="titulo-registros" class="card-title mb-0"><?= $usuarios ? 'Usuários cadastrados' : 'Registros já cadastrados' ?></h2>
        <?php if ($registros && ($podeEditar || $podeExcluir)): ?>
            <p class="text-muted small mt-1 mb-0">Use a busca para encontrar um registro e escolha uma ação na última coluna.</p>
        <?php endif ?>
    </div>
    <?php if (!$registros): ?>
        <div class="card-body"><p class="text-muted mb-0">Nenhum registro cadastrado.<?= $podeEditar ? ' Use o formulário acima para adicionar o primeiro.' : '' ?></p></div>
    <?php else: ?>
<div class="card-body table-responsive" tabindex="0" role="region" aria-labelledby="titulo-registros">
    <table class="table table-striped table-hover table-bordered" id="tabelaDados">
        <thead class="table-primary">
            <tr>
                <?php foreach ($camposVisiveis as $campo): ?>
                    <th scope="col"><?= esc($campo === $campoId ? 'ID' : ($rotulosTabela[$campo] ?? ucfirst($campo))) ?></th>
                <?php endforeach; ?>
                <?php if ($podeEditar || $podeExcluir): ?><th scope="col">Ações</th><?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $linha): ?>
                <tr>
                    <?php foreach ($camposVisiveis as $campo): ?>
                        <td>
                            <?php if ($tabela === 'ref_cor' && $campo === 'hexadecimal'): ?>
                                <?php $cor = '#' . ltrim(trim((string) $linha[$campo]), '#'); ?>
                                <?php if (preg_match('/\A#(?:[0-9a-f]{3}|[0-9a-f]{6})\z/i', $cor)): ?>
                                    <span class="color-swatch" style="width: 1.25rem; height: 1.25rem; vertical-align: middle; background-color: <?= esc($cor, 'attr') ?>" title="<?= esc($cor, 'attr') ?>" aria-hidden="true"></span>
                                    <span class="visually-hidden"><?= esc($cor) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Cor inválida</span>
                                <?php endif ?>
                            <?php else: ?>
                                <?= esc($linha[$campo]) ?>
                            <?php endif ?>
                        </td>
                    <?php endforeach; ?>
                    <?php if ($podeEditar || $podeExcluir): ?><td>
                        <?php if ($podeEditar): ?>
                        <a href="<?=url_to('restrito.config.edit', base64_encode($titulo), base64_encode($tabela), base64_encode($linha[getIdNameTabela($tabela)]) )?>" 
                           class="btn btn-sm btn-outline-primary me-1">
                            <i class="fe fe-edit"></i> Editar
                        </a>
                        <?php if ($usuarios): ?>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modal-senha-usuario"
                                data-usuario-id="<?= (int) $linha[$campoId] ?>" data-usuario-nome="<?= esc($linha['nome'], 'attr') ?>">Alterar senha</button>
                        <?php endif ?>
                        <?php endif ?>
                        <?php
                        if ($podeExcluir && ($tabela !== 'tb_usuario' || (
                            (int) $linha['id_usuario'] !== 1
                            && (int) $linha['id_usuario'] !== (int) session('usuario_logado.id_usuario')
                        ))) {
                        ?>
                        <button type="button" class="btn btn-sm btn-outline-danger modalExcluir"
                            data-tabela-excluir="<?= esc($tabela, 'attr') ?>"
                            data-id-excluir="<?= esc($linha[getIdNameTabela($tabela)], 'attr') ?>"
                            data-url-excluir="<?= url_to('restrito.config.excluir') ?>"
                            data-csrf-nome="<?= esc(csrf_token(), 'attr') ?>"
                            data-csrf-valor="<?= esc(csrf_hash(), 'attr') ?>"
                            data-mensagem-excluir="Você confirma a exclusão deste cadastro?">
                            <i class="fe fe-trash" aria-hidden="true"></i> Excluir
                        </button>
                        <?php
                        }
                        ?>
                    </td><?php endif ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <?php endif ?>
</section>

<?php if ($usuarios): ?>
    <section class="card" aria-labelledby="titulo-usuarios-excluidos">
        <div class="card-header">
            <h2 class="card-title mb-0" id="titulo-usuarios-excluidos">Usuários excluídos</h2>
            <p class="text-muted small mt-1 mb-0">Restaure uma conta para devolver o acesso ao sistema.</p>
        </div>
        <?php if (!$usuariosExcluidos): ?>
            <div class="card-body"><p class="text-muted mb-0">Nenhum usuário excluído.</p></div>
        <?php else: ?>
            <div class="table-responsive" tabindex="0" role="region" aria-labelledby="titulo-usuarios-excluidos">
                <table class="table align-middle">
                    <thead><tr><th scope="col">Nome</th><th scope="col">E-mail</th><th scope="col">Excluído em</th><th scope="col">Ações</th></tr></thead>
                    <tbody>
                    <?php foreach ($usuariosExcluidos as $excluido): ?>
                        <?php $dataExclusao = strtotime($excluido['deleted_at']); ?>
                        <tr>
                            <td><?= esc($excluido['nome']) ?></td>
                            <td><?= esc($excluido['email']) ?></td>
                            <td><time datetime="<?= date('c', $dataExclusao) ?>"><?= date('d/m/Y \à\s H:i', $dataExclusao) ?></time></td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if ($podeEditar): ?>
                                        <form action="<?= url_to('restrito.config.usuario.restaurar') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= (int) $excluido['id_usuario'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Restaurar</button>
                                        </form>
                                    <?php endif ?>
                                    <?php if ($podeExcluirUsuarioDefinitivamente): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger modalExcluir"
                                            data-id-excluir="<?= (int) $excluido['id_usuario'] ?>"
                                            data-url-excluir="<?= url_to('restrito.config.usuario.excluirDefinitivamente') ?>"
                                            data-csrf-nome="<?= esc(csrf_token(), 'attr') ?>"
                                            data-csrf-valor="<?= esc(csrf_hash(), 'attr') ?>"
                                            data-titulo-excluir="Excluir usuário definitivamente?"
                                            data-confirmar-excluir="Excluir definitivamente"
                                            data-mensagem-excluir="<?= esc('A conta de ' . $excluido['nome'] . ' será removida e não poderá mais ser restaurada.', 'attr') ?>">Excluir definitivamente</button>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </section>
    <?php if ($podeEditar): ?>
        <?php $erroSenha = session()->getFlashdata('erro_senha_usuario'); ?>
        <div class="modal fade" id="modal-senha-usuario" tabindex="-1" aria-labelledby="titulo-modal-senha">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="post" action="<?= url_to('restrito.config.usuario.senha') ?>"
                    data-usuario-erro="<?= (int) ($erroSenha['id'] ?? 0) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="titulo-modal-senha">Alterar senha</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Usuário: <strong id="nome-usuario-senha"></strong></p>
                        <p class="alert alert-danger" id="erro-senha-usuario" role="alert" hidden><?= esc($erroSenha['mensagem'] ?? '') ?></p>
                        <div class="mb-3">
                            <label for="nova-senha-usuario" class="form-label">Nova senha</label>
                            <input type="password" class="form-control" id="nova-senha-usuario" name="nova_senha" autocomplete="new-password" minlength="3" maxlength="72" required>
                        </div>
                        <div>
                            <label for="confirma-senha-usuario" class="form-label">Confirme a nova senha</label>
                            <input type="password" class="form-control" id="confirma-senha-usuario" name="confirma_senha" autocomplete="new-password" minlength="3" maxlength="72" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar nova senha</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>
<?= $this->endSection() ?>


<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const modalSenha = document.getElementById('modal-senha-usuario');
    if (modalSenha) {
        const formSenha = modalSenha.querySelector('form');
        const novaSenha = formSenha.elements.nova_senha;
        const confirmacao = formSenha.elements.confirma_senha;
        const erroSenha = document.getElementById('erro-senha-usuario');
        modalSenha.addEventListener('show.bs.modal', ({relatedTarget}) => {
            formSenha.reset();
            confirmacao.setCustomValidity('');
            formSenha.elements.id.value = relatedTarget.dataset.usuarioId;
            document.getElementById('nome-usuario-senha').textContent = relatedTarget.dataset.usuarioNome;
            erroSenha.hidden = formSenha.dataset.usuarioErro !== relatedTarget.dataset.usuarioId;
        });
        modalSenha.addEventListener('shown.bs.modal', () => novaSenha.focus());
        modalSenha.addEventListener('hidden.bs.modal', () => {
            formSenha.dataset.usuarioErro = '';
            formSenha.reset();
        });
        formSenha.addEventListener('input', () => {
            confirmacao.setCustomValidity(confirmacao.value && confirmacao.value !== novaSenha.value ? 'As duas senhas precisam ser iguais.' : '');
        });
        const botaoComErro = document.querySelector('[data-usuario-id="' + formSenha.dataset.usuarioErro + '"]');
        if (botaoComErro) bootstrap.Modal.getOrCreateInstance(modalSenha).show(botaoComErro);
    }

    const cargoUsuario = document.getElementById('cargo');
    const painelPermissoes = document.getElementById('permissoes-usuario');
    if (cargoUsuario && painelPermissoes) {
        const seletorPermissoes = 'input[name="permissoes[]"]';
        const permissoesUsuario = [...painelPermissoes.querySelectorAll(seletorPermissoes)];
        const seletores = [[document.getElementById('selecionar-todas-permissoes'), permissoesUsuario]];
        for (const grupo of painelPermissoes.querySelectorAll('[data-grupo-permissoes]')) {
            seletores.push([grupo.querySelector('[data-selecionar-grupo]'), [...grupo.querySelectorAll(seletorPermissoes)]]);
        }

        function atualizarSeletores() {
            for (const [seletor, opcoes] of seletores) {
                const marcadas = opcoes.filter(opcao => opcao.checked).length;
                seletor.checked = marcadas === opcoes.length;
                seletor.indeterminate = marcadas > 0 && marcadas < opcoes.length;
                seletor.disabled = cargoUsuario.value === 'Administrador';
            }
        }

        for (const [seletor, opcoes] of seletores) {
            seletor.addEventListener('change', () => {
                for (const opcao of opcoes) opcao.checked = seletor.checked;
            });
        }
        painelPermissoes.addEventListener('change', atualizarSeletores);
        cargoUsuario.addEventListener('change', () => {
            const administrador = cargoUsuario.value === 'Administrador';
            for (const opcao of permissoesUsuario) {
                if (administrador) opcao.dataset.selecionada = opcao.checked ? '1' : '0';
                opcao.checked = administrador || opcao.dataset.selecionada === '1';
                opcao.disabled = administrador;
            }
            atualizarSeletores();
        });
        atualizarSeletores();
    }

    const tabelaCadastros = $('#tabelaDados');
    if (tabelaCadastros.length) {
        const chavePaginacao = 'estoque.config.resultadosPorPagina';
        const opcoesPaginacao = [10, 25, 50, 100];
        let preferencia;
        try {
            preferencia = Number(localStorage.getItem(chavePaginacao));
        } catch {}

        tabelaCadastros.DataTable({
            lengthMenu: opcoesPaginacao,
            pageLength: opcoesPaginacao.includes(preferencia) ? preferencia : 25
        });
        tabelaCadastros.on('length.dt', (_, configuracao, quantidade) => {
            try {
                localStorage.setItem(chavePaginacao, String(quantidade));
            } catch {}
        });
    }

    document.querySelectorAll('.toggle-password').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const input = document.querySelector(this.getAttribute('data-target'));
            const mostrar = input.type === 'password';
            input.type = mostrar ? 'text' : 'password';
            this.textContent = mostrar ? 'Ocultar' : 'Mostrar';
            this.setAttribute('aria-pressed', String(mostrar));
        });
    });
</script>
<?= $this->endSection() ?>
