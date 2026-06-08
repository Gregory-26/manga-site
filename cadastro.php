<?php
// Aqui o usuário cria uma conta no site.
// A senha é salva de forma segura usando password_hash().
// Campos obrigatórios: nome, usuário, e-mail, senha

require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

// Se o usuário já está logado, não precisa se cadastrar de novo
if (usuarioLogado()) {
    redirecionar('index.php');
}

$erro    = '';
$sucesso = '';

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pegar e limpar os dados do formulário
    $nome    = trim($_POST['nome']    ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $email   = trim($_POST['email']   ?? '');
    $senha   = trim($_POST['senha']   ?? '');
    $confirmar_senha = trim($_POST['confirmar_senha'] ?? '');

    // --- Validações ---

    if (empty($nome) || empty($usuario) || empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos obrigatórios.';

    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';

    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // FILTER_VALIDATE_EMAIL verifica se o e-mail tem formato válido
        $erro = 'Digite um e-mail válido.';

    } else {
        // Verificar se o e-mail ou usuário já estão cadastrados
        $email_seguro   = mysqli_real_escape_string($conexao, $email);
        $usuario_seguro = mysqli_real_escape_string($conexao, $usuario);

        $sql_check = "SELECT id FROM usuarios WHERE email = '$email_seguro' OR usuario = '$usuario_seguro' LIMIT 1";
        $resultado_check = mysqli_query($conexao, $sql_check);

        if (mysqli_num_rows($resultado_check) > 0) {
            $erro = 'Esse e-mail ou nome de usuário já está em uso.';

        } else {
            // --- Tudo certo! Salvar o usuário no banco ---
            // password_hash() — COMO FUNCIONA
            // Nunca salvo a senha como texto puro no banco de dados!
            // password_hash() transforma "minha_senha" em algo como:
            //   "$2y$10$abcdef123456...xyz"
            // PASSWORD_DEFAULT usa o algoritmo mais seguro disponível.
            // O hash é diferente toda vez que é gerado (por causa do "salt"),
            // mas password_verify() consegue verificar mesmo assim.
            // Se eu precisar mudar o algoritmo no futuro,
            // mudo só aqui e o password_verify continua funcionando.
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Escapar todos os dados antes de inserir no banco
            $nome_seguro  = mysqli_real_escape_string($conexao, $nome);

            $sql_insert = "
                INSERT INTO usuarios (nome, usuario, email, senha)
                VALUES ('$nome_seguro', '$usuario_seguro', '$email_seguro', '$senha_hash')
            ";

            $inseriu = mysqli_query($conexao, $sql_insert);

            if ($inseriu) {
                // Cadastro realizado com sucesso!
                // Fazer login automático após o cadastro
                $novo_id = mysqli_insert_id($conexao); // Pegar o ID do usuário recém-criado

                $_SESSION['usuario_id']   = $novo_id;
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_user'] = $usuario;

                redirecionar('index.php');

            } else {
                // Algo deu errado ao inserir no banco
                // Lembrete: verificar a estrutura da tabela se isso acontecer
                $erro = 'Erro ao criar a conta. Tente novamente.';
            }
        }
    }
}

$titulo_pagina = 'Criar Conta';
require_once 'includes/header.php';
?>

<div class="pagina-form">
    <div class="caixa-form">

        <h1 class="form-titulo">✨ Criar Conta</h1>
        <p class="form-subtitulo">É gratuito! Salve seus mangás favoritos e acompanhe sua leitura.</p>

        <!-- Mensagem de erro -->
        <?php if (!empty($erro)): ?>
            <div class="mensagem mensagem-erro"><?= sanitizar($erro) ?></div>
        <?php endif; ?>

        <form action="" method="POST">

            <div class="form-grupo">
                <label for="nome">Nome completo *</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Seu nome"
                    value="<?= sanitizar($_POST['nome'] ?? '') ?>"
                    required
                    maxlength="100"
                >
            </div>

            <div class="form-grupo">
                <label for="usuario">Nome de usuário *</label>
                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    placeholder="@seu_usuario"
                    value="<?= sanitizar($_POST['usuario'] ?? '') ?>"
                    required
                    maxlength="50"
                >
            </div>

            <div class="form-grupo">
                <label for="email">E-mail *</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="seu@email.com"
                    value="<?= sanitizar($_POST['email'] ?? '') ?>"
                    required
                    maxlength="150"
                    autocomplete="email"
                >
            </div>

            <div class="form-grupo">
                <label for="senha">Senha * (mínimo 6 caracteres)</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="••••••••"
                    required
                    minlength="6"
                    autocomplete="new-password"
                >
            </div>

            <div class="form-grupo">
                <label for="confirmar_senha">Confirmar senha *</label>
                <input
                    type="password"
                    id="confirmar_senha"
                    name="confirmar_senha"
                    placeholder="••••••••"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn btn-primario" style="width:100%; justify-content:center;">
                Criar minha conta
            </button>

        </form>

        <p class="form-link">
            Já tem conta? <a href="login.php">Entrar</a>
        </p>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
