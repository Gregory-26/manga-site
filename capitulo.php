<?php
// Recebe o ID do capítulo pela URL: capitulo.php?id=1
// As imagens ficam em pastas definidas no banco (pasta_imagens).
// Ex: assets/imagens/capitulos/naruto/cap1/
// Dentro dessa pasta, as imagens devem ter nomes como:
//   01.jpg, 02.jpg, 03.jpg... (em ordem crescente)
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';
// Pegar e validar o ID do capítulo
$capitulo_id = (int) ($_GET['id'] ?? 0);

if ($capitulo_id <= 0) {
    redirecionar('index.php');
}

// Buscar dados do capítulo
$capitulo = buscarCapituloPorId($conexao, $capitulo_id);

if (!$capitulo) {
    redirecionar('index.php');
}

// Buscar o mangá ao qual esse capítulo pertence
$manga = buscarMangaPorId($conexao, $capitulo['manga_id']);

if (!$manga) {
    redirecionar('index.php');
}

// Buscar todos os capítulos do mangá para navegação
$todos_capitulos = buscarCapitulosPorManga($conexao, $manga['id']);

// Encontrar o índice do capítulo atual na lista
// Isso serve para saber qual é o próximo e o anterior
$indice_atual     = -1;
$capitulo_anterior = null;
$proximo_capitulo  = null;

foreach ($todos_capitulos as $indice => $cap) {
    if ($cap['id'] == $capitulo_id) {
        $indice_atual = $indice;
        break;
    }
}

// Definir capítulos anterior e próximo
if ($indice_atual > 0) {
    $capitulo_anterior = $todos_capitulos[$indice_atual - 1];
}
if ($indice_atual < count($todos_capitulos) - 1) {
    $proximo_capitulo = $todos_capitulos[$indice_atual + 1];
}

// Buscar imagens do capítulo
// As imagens ficam na pasta definida em "pasta_imagens" no banco.
// Essa função lê os arquivos de imagem presentes na pasta.
$imagens = [];
$pasta = $capitulo['pasta_imagens'] ?? '';

if (!empty($pasta) && is_dir($pasta)) {
    // Listar arquivos de imagem na pasta
    $extensoes_validas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $arquivos = scandir($pasta);

    foreach ($arquivos as $arquivo) {
        // Ignorar . e ..
        if ($arquivo === '.' || $arquivo === '..') continue;

        $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
        if (in_array($extensao, $extensoes_validas)) {
            $imagens[] = $pasta . $arquivo;
        }
    }

    // Ordenar as imagens pelo nome (01.jpg, 02.jpg, etc.)
    sort($imagens);
}

$titulo_pagina = 'Cap. ' . number_format($capitulo['numero'], 0) . ' — ' . $manga['titulo'];
require_once 'includes/header.php';
?>

<!-- Estilo específico para a página de leitura -->
<!-- Deixei o fundo mais escuro para não cansar a vista -->
<style>
    body {
        background-color: #0a0a0a;
    }
    .leitor-imagens img {
        display: block;
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
    }
</style>

<div class="leitor">

    <!-- Título do capítulo -->
    <div class="leitor-titulo">
        <a
            href="manga.php?id=<?= $manga['id'] ?>"
            style="color: var(--cinza-claro); font-size: 0.9rem; display: block; margin-bottom: 8px;"
        >
            ← Voltar para <?= sanitizar($manga['titulo']) ?>
        </a>
        <h2><?= sanitizar($manga['titulo']) ?></h2>
        <p style="color: var(--cinza-claro); margin-top: 8px;">
            Capítulo <?= number_format($capitulo['numero'], 0) ?>
            <?= !empty($capitulo['titulo']) ? '— ' . sanitizar($capitulo['titulo']) : '' ?>
        </p>
    </div>

    <!-- Navegação no topo -->
    <div class="leitor-navegacao" style="margin-bottom: 30px;">
        <?php if ($capitulo_anterior): ?>
            <a href="capitulo.php?id=<?= $capitulo_anterior['id'] ?>" class="btn btn-secundario">
                ← Cap. <?= number_format($capitulo_anterior['numero'], 0) ?>
            </a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>

        <?php if ($proximo_capitulo): ?>
            <a href="capitulo.php?id=<?= $proximo_capitulo['id'] ?>" class="btn btn-primario">
                Cap. <?= number_format($proximo_capitulo['numero'], 0) ?> →
            </a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>
    </div>

    <!-- Imagens do capítulo -->
    <div class="leitor-imagens">
        <?php if (!empty($imagens)): ?>
            <?php foreach ($imagens as $indice_img => $caminho_imagem): ?>
                <img
                    src="<?= sanitizar($caminho_imagem) ?>"
                    alt="Página <?= $indice_img + 1 ?> do Capítulo <?= number_format($capitulo['numero'], 0) ?>"
                    loading="lazy"
                >
            <?php endforeach; ?>

        <?php else: ?>
            <!-- Exibido quando não há imagens na pasta -->
            <div class="estado-vazio">
                <span class="estado-vazio-icone">🖼️</span>
                <h3>Imagens não encontradas</h3>
                <p style="color: var(--cinza-claro);">
                    As imagens deste capítulo ainda não foram adicionadas.<br>
                    Pasta esperada: <code style="color: var(--roxo-neon);"><?= sanitizar($pasta ?: 'não definida') ?></code>
                </p>
                <p style="color: var(--cinza-claro); font-size: 0.85rem; margin-top: 12px;">
                    Coloque imagens com nomes como <strong>01.jpg, 02.jpg...</strong> nessa pasta.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Navegação no final da página -->
    <div class="leitor-navegacao" style="padding-top: 20px;">
        <?php if ($capitulo_anterior): ?>
            <a href="capitulo.php?id=<?= $capitulo_anterior['id'] ?>" class="btn btn-secundario">
                ← Cap. <?= number_format($capitulo_anterior['numero'], 0) ?>
            </a>
        <?php else: ?>
            <a href="manga.php?id=<?= $manga['id'] ?>" class="btn btn-secundario">
                ← Voltar ao Mangá
            </a>
        <?php endif; ?>

        <?php if ($proximo_capitulo): ?>
            <a href="capitulo.php?id=<?= $proximo_capitulo['id'] ?>" class="btn btn-primario">
                Cap. <?= number_format($proximo_capitulo['numero'], 0) ?> →
            </a>
        <?php else: ?>
            <!-- Fim do mangá (ou último capítulo disponível) -->
            <div style="text-align: right;">
                <p style="color: var(--cinza-claro); font-size: 0.9rem; margin-bottom: 8px;">
                    Fim dos capítulos disponíveis
                </p>
                <a href="manga.php?id=<?= $manga['id'] ?>" class="btn btn-primario">
                    Ver todos os capítulos
                </a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
