<?php
// Esse arquivo é o cabeçalho padrão do site.
// Incluo ele no início de cada página para não repetir
// o menu e os links de CSS toda hora.
// Se eu quiser mudar o menu ou a logo, altero só aqui.
// Checar se as funções já foram carregadas (evita carregar duas vezes)
if (!function_exists('usuarioLogado')) {
    require_once __DIR__ . '/funcoes.php';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo_pagina) ? sanitizar($titulo_pagina) . ' — ' : '' ?>MangaVerse</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Fonte Google: Rajdhani para títulos, Nunito para corpo -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- ===== CABEÇALHO / NAVBAR ===== -->
<header class="navbar">
    <div class="container">

        <!-- Logo do site -->
        <a href="index.php" class="logo">
            <span class="logo-icone">⛩</span>
            <span class="logo-texto">MangaVerse</span>
        </a>

        <!-- Menu de navegação principal -->
        <nav class="menu" id="menu">
            <a href="index.php">Início</a>
            <a href="busca.php">Buscar</a>
            <a href="generos.php">Gêneros</a>

            <?php if (usuarioLogado()): ?>
                <!-- Links visíveis apenas para quem está logado -->
                <a href="favoritos.php">Favoritos</a>
                <a href="perfil.php">Perfil</a>
                <a href="logout.php" class="btn-nav">Sair</a>
            <?php else: ?>
                <!-- Links visíveis para quem NÃO está logado -->
                <a href="login.php" class="btn-nav">Entrar</a>
                <a href="cadastro.php" class="btn-nav btn-destaque">Cadastrar</a>
            <?php endif; ?>
        </nav>

        <!-- Botão para abrir/fechar menu no celular -->
        <!-- O JavaScript em script.js vai controlar a abertura -->
        <button class="btn-menu-mobile" id="btn-menu-mobile" aria-label="Abrir menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>
</header>

<!-- Espaço para o conteúdo não ficar atrás da navbar fixa -->
<div class="espaco-navbar"></div>
