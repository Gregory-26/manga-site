<?php
// login.php — Página de Login
// Aqui o usuário informa e-mail e senha para entrar no site.
// Após o login, ele é redirecionado para a página inicial.
// Se o login não funcionar:
// 1. Verificar se o e-mail existe no banco
// 2. Verificar se a senha está correta (password_verify)
// 3. Verificar se a sessão está iniciando corretamente

require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

// Se o usuário já está logado, redirecionar para a home
// Não faz sentido exibir a página de login para quem já entrou
if (usuarioLogado()) {
    redirecionar('index.php');
}

$erro    = ''; // Mensagem de erro (senha errada, usuário não encontrado, etc.)
$sucesso = ''; // Mensagem de sucesso (não muito usada aqui, mas boa prática ter)

// Processar o formulário quando o usuário clicar em "Entrar"
// $_SERVER['REQUEST_METHOD'] é 'POST' quando um formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pegar os dados digitados no formulário
    // trim() remove espaços no início e no fim
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Verificar se os campos foram preenchidos
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos.';

    } else {
        // Buscar o usuário no banco pelo e-mail
        // mysqli_real_escape_string() protege contra SQL Injection
        $email_seguro = mysqli_real_escape_string($conexao, $email);
        $sql = "SELECT * FROM usuarios WHERE email = '$email_seguro' LIMIT 1";
        $resultado = mysqli_query($conexao, $sql);
        $usuario_encontrado = mysqli_fetch_assoc($resultado);

        if (!$usuario_encontrado) {
            // E-mail não existe no banco
            $erro = 'E-mail ou senha incorretos.';

        } else {
            // password_verify() — COMO FUNCIONA?
            // Quando o usuário se cadastrou, a senha foi salva como hash
            // usando password_hash(). Por exemplo:
            //   "minha_senha" vira "$2y$10$abc123xyz..." no banco
            // password_verify() compara a senha digitada AGORA
            // com o hash salvo no banco. Se bater, retorna true.
            // IMPORTANTE: nunca armazene a senha como texto puro!
            if (password_verify($senha, $usuario_encontrado['senha'])) {
                // Senha correta! Iniciar a sessão do usuário.

                // Salvar dados importantes na sessão
                // $_SESSION fica disponível em todas as páginas até o logout
                $_SESSION['usuario_id']   = $usuario_encontrado['id'];
                $_SESSION['usuario_nome'] = $usuario_encontrado['nome'];
                $_SESSION['usuario_user'] = $usuario_encontrado['usuario'];

                // Redirecionar para a home após login bem-sucedido
                redirecionar('index.php');

            } else {
                // Senha incorreta
                $erro = 'E-mail ou senha incorretos.';
            }
        }
    }
}

$titulo_pagina = 'Entrar';
require_once 'includes/header.php';
?>

<div class="pagina-form">
    <div class="caixa-form">

        <h1 class="form-titulo">⛩ Entrar</h1>
        <p class="form-subtitulo">Acesse sua conta para salvar favoritos e acompanhar seus mangás.</p>

        <!-- Exibir mensagem de erro, se houver -->
        <?php if (!empty($erro)): ?>
            <div class="mensagem mensagem-erro"><?= sanitizar($erro) ?></div>
        <?php endif; ?>

        <!-- Formulário de login -->
        <!-- action="" significa que envia para a própria página (login.php) -->
        <form action="" method="POST">

            <div class="form-grupo">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="seu@email.com"
                    value="<?= sanitizar($_POST['email'] ?? '') ?>"
                    required
                    autocomplete="email"
                >
                <!-- value mantém o e-mail digitado caso o formulário retorne com erro -->
            </div>

            <div class="form-grupo">
                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn btn-primario" style="width:100%; justify-content:center;">
                Entrar
            </button>

        </form>

        <p class="form-link">
            Não tem conta? <a href="cadastro.php">Cadastre-se grátis</a>
        </p>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
