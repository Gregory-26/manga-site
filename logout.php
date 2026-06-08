<?php
// logout.php — Encerrar Sessão
// Quando o usuário clica em "Sair", ele é redirecionado aqui.
// Esse arquivo limpa a sessão e redireciona para a home.
// Não tem HTML — apenas PHP.

require_once 'includes/funcoes.php';

// Destruir a sessão completamente
// Isso apaga todas as variáveis $_SESSION salvas durante o login
session_destroy();

// Redirecionar para a página inicial após o logout
redirecionar('index.php');
