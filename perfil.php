<?php
// Exibe informações do usuário logado:
// nome, usuário, data de cadastro e quantidade de favoritos.
// Só pode ser acessada por usuários logados.

require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

// Redirecionar para o login se não estiver logado
if (!usuarioLogado()) {
    redirecionar('login.php');
}

// Buscar os dados atualizados do usuário no banco
// (melhor que confiar só na sessão, que pode estar desatualizada)
$usuario = buscarUsuarioPorId($conexao, $_SESSION['usuario_id']);

// Se o usuário não existir mais no banco (conta deletada, etc.)
if (!$usuario) {
    redirecionar('logout.php');
}

// Contar quantos favoritos o usuário tem
$total_favoritos = contarFavoritosDoUsuario($conexao, $usuario['id']);

// Formatar a data de cadastro de forma mais amigável
// Ex: "2024-01-15" vira "15 de janeiro de 2024"
$data_formatada = '';
if (!empty($usuario['data_cadastro'])) {
    $timestamp = strtotime($usuario['data_cadastro']);
    // setlocale e strftime precisam de configuração adicional
    // então vou usar uma abordagem mais simples
    $meses = [
        1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
        5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
        9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
    ];
    $dia  = date('d', $timestamp);
    $mes  = $meses[(int) date('m', $timestamp)];
    $ano  = date('Y', $timestamp);
    $data_formatada = "$dia de $mes de $ano";
}

$titulo_pagina = 'Meu Perfil';
require_once 'includes/header.php';
?>

<div class="container" style="padding-top: 40px; padding-bottom: 40px;">

    <!-- Card de perfil -->
    <div class="perfil-card">

        <!-- Avatar com a inicial do nome -->
        <div class="perfil-avatar">
            <?= mb_strtoupper(mb_substr($usuario['nome'], 0, 1)) ?>
        </div>

        <h1 class="perfil-nome"><?= sanitizar($usuario['nome']) ?></h1>
        <p class="perfil-usuario">@<?= sanitizar($usuario['usuario']) ?></p>

        <p style="color: var(--cinza-claro); font-size: 0.9rem;">
            📧 <?= sanitizar($usuario['email']) ?>
        </p>

        <?php if (!empty($data_formatada)): ?>
        <p style="color: var(--cinza-claro); font-size: 0.85rem; margin-top: 8px;">
            📅 Membro desde <?= $data_formatada ?>
        </p>
        <?php endif; ?>

        <!-- Estatísticas -->
        <div class="perfil-stats">
            <div class="stat-item">
                <span class="stat-numero"><?= $total_favoritos ?></span>
                <span class="stat-label">Favorito<?= $total_favoritos !== 1 ? 's' : '' ?></span>
            </div>
        </div>

        <!-- Botão para ver favoritos -->
        <div style="margin-top: 32px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="favoritos.php" class="btn btn-primario">❤️ Meus Favoritos</a>
            <a href="logout.php" class="btn btn-secundario">Sair da conta</a>
        </div>

    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
