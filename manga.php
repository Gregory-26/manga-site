<?php
// manga.php — Página de Detalhes do Mangá
// Exibe informações completas de um mangá:
// capa, título, descrição, gênero, autor e lista de capítulos.
// Recebe o ID do mangá pela URL: manga.php?id=1

require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

// Pegar o ID da URL e converter para inteiro (segurança)
$manga_id = (int) ($_GET['id'] ?? 0);

// Se não passar um ID válido, redirecionar para a home
if ($manga_id <= 0) {
    redirecionar('index.php');
}

// Buscar os dados do mangá no banco
// Lembrete para mim:
// Se $manga estiver vazio, o ID não existe no banco.
$manga = buscarMangaPorId($conexao, $manga_id);

// Se o mangá não existir, redirecionar para a home
if (!$manga) {
    redirecionar('index.php');
}

// Buscar os capítulos desse mangá em ordem crescente
$capitulos = buscarCapitulosPorManga($conexao, $manga_id);

// Verificar se o mangá já está nos favoritos do usuário logado
$ja_favoritado = false;
if (usuarioLogado()) {
    $ja_favoritado = ehFavorito($conexao, $_SESSION['usuario_id'], $manga_id);
}

// Processar ação de favoritar/desfavoritar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && usuarioLogado()) {
    $acao = $_POST['acao'] ?? '';
    $usuario_id = (int) $_SESSION['usuario_id'];

    if ($acao === 'adicionar') {
        $sql = "INSERT IGNORE INTO favoritos (usuario_id, manga_id) VALUES ($usuario_id, $manga_id)";
        mysqli_query($conexao, $sql);
    } elseif ($acao === 'remover') {
        $sql = "DELETE FROM favoritos WHERE usuario_id = $usuario_id AND manga_id = $manga_id";
        mysqli_query($conexao, $sql);
    }

    // Recarregar a página após a ação para atualizar o estado do botão
    redirecionar("manga.php?id=$manga_id");
}

$titulo_pagina = $manga['titulo'];
require_once 'includes/header.php';
?>

<div class="container">

    <!-- ===== TOPO: CAPA + INFORMAÇÕES ===== -->
    <div class="manga-topo">

        <!-- Capa do mangá -->
        <div class="manga-capa-grande">
            <?php if (!empty($manga['capa']) && file_exists($manga['capa'])): ?>
                <img
                    src="<?= sanitizar($manga['capa']) ?>"
                    alt="Capa de <?= sanitizar($manga['titulo']) ?>"
                >
            <?php else: ?>
                <!-- Placeholder para quando não tem imagem -->
                <div class="capa-placeholder" style="aspect-ratio: 2/3; font-size: 5rem;">
                    📚
                </div>
            <?php endif; ?>
        </div>

        <!-- Informações -->
        <div class="manga-info">
            <h1><?= sanitizar($manga['titulo']) ?></h1>

            <!-- Tags de gênero e autor -->
            <div class="manga-tags">
                <?php if (!empty($manga['genero'])): ?>
                    <a href="generos.php?genero=<?= urlencode($manga['genero']) ?>" class="tag">
                        🏷️ <?= sanitizar($manga['genero']) ?>
                    </a>
                <?php endif; ?>

                <?php if (!empty($manga['autor'])): ?>
                    <span class="tag">✍️ <?= sanitizar($manga['autor']) ?></span>
                <?php endif; ?>

                <span class="tag">📖 <?= count($capitulos) ?> capítulo<?= count($capitulos) !== 1 ? 's' : '' ?></span>
            </div>

            <!-- Descrição -->
            <?php if (!empty($manga['descricao'])): ?>
                <p class="manga-descricao"><?= nl2br(sanitizar($manga['descricao'])) ?></p>
            <?php endif; ?>

            <!-- Botões de ação -->
            <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px;">

                <!-- Botão para começar a ler (vai para o primeiro capítulo) -->
                <?php if (!empty($capitulos)): ?>
                    <a href="capitulo.php?id=<?= $capitulos[0]['id'] ?>" class="btn btn-primario">
                        📖 Começar a Ler
                    </a>
                <?php endif; ?>

                <!-- Botão de favoritar (só aparece para usuários logados) -->
                <?php if (usuarioLogado()): ?>
                    <form action="manga.php?id=<?= $manga_id ?>" method="POST">
                        <?php if ($ja_favoritado): ?>
                            <input type="hidden" name="acao" value="remover">
                            <button type="submit" class="btn btn-perigo">
                                💔 Remover Favorito
                            </button>
                        <?php else: ?>
                            <input type="hidden" name="acao" value="adicionar">
                            <button type="submit" class="btn btn-secundario">
                                ❤️ Favoritar
                            </button>
                        <?php endif; ?>
                    </form>

                <?php else: ?>
                    <!-- Se não estiver logado, mostrar link para login -->
                    <a href="login.php" class="btn btn-secundario">
                        ❤️ Favoritar (faça login)
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!--  LISTA DE CAPÍTULOS  -->
    <div class="lista-capitulos" style="padding-bottom: 60px;">
        <h2 class="titulo-secao">📚 Capítulos</h2>

        <?php if (!empty($capitulos)): ?>
            <?php foreach ($capitulos as $capitulo): ?>
                <a href="capitulo.php?id=<?= $capitulo['id'] ?>" class="capitulo-item">
                    <span class="capitulo-numero">Cap. <?= number_format($capitulo['numero'], 0) ?></span>
                    <span class="capitulo-titulo">
                        <?= !empty($capitulo['titulo']) ? sanitizar($capitulo['titulo']) : 'Capítulo ' . number_format($capitulo['numero'], 0) ?>
                    </span>
                    <span style="color: var(--cinza-escuro); font-size: 0.85rem;">→</span>
                </a>
            <?php endforeach; ?>

        <?php else: ?>
            <!-- Se não tem capítulos cadastrados -->
            <div class="estado-vazio">
                <span class="estado-vazio-icone">📦</span>
                <h3>Nenhum capítulo disponível ainda</h3>
                <p>Os capítulos serão adicionados em breve.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
