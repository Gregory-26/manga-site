<?php
// Duas funções nessa página:
// 1. Sem parâmetro: exibe todos os gêneros disponíveis
// 2. Com ?genero=Ação: exibe os mangás daquele gênero

require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

// Verificar se o usuário está filtrando por um gênero específico
$genero_selecionado = trim($_GET['genero'] ?? '');

// Ícones para cada gênero
$icones_generos = [
    'Ação'         => '⚔️',
    'Romance'      => '💕',
    'Comédia'      => '😂',
    'Fantasia'     => '🧙',
    'Isekai'       => '🌀',
    'Shounen'      => '🔥',
    'Seinen'       => '🎭',
    'Drama'        => '🎬',
    'Terror'       => '👻',
    'Slice of Life'=> '🌸',
];

if (!empty($genero_selecionado)) {
    // Buscar mangás do gênero selecionado
    $mangas_do_genero = buscarMangasPorGenero($conexao, $genero_selecionado);
    $titulo_pagina = 'Gênero: ' . $genero_selecionado;
} else {
    // Exibir todos os gêneros
    $todos_generos = buscarGeneros($conexao);
    $titulo_pagina = 'Gêneros';
}

require_once 'includes/header.php';
?>

<div class="container" style="padding-top: 40px; padding-bottom: 40px;">

    <?php if (!empty($genero_selecionado)): ?>
        <!-- ===== MANGÁS DE UM GÊNERO ESPECÍFICO ===== -->

        <div style="margin-bottom: 24px;">
            <a href="generos.php" style="color: var(--cinza-claro); font-size: 0.9rem;">
                ← Voltar aos gêneros
            </a>
        </div>

        <h1 class="titulo-secao">
            <?= $icones_generos[$genero_selecionado] ?? '📚' ?>
            <?= sanitizar($genero_selecionado) ?>
        </h1>

        <?php if (!empty($mangas_do_genero)): ?>
            <p style="color: var(--cinza-claro); margin-bottom: 24px;">
                <?= count($mangas_do_genero) ?> título<?= count($mangas_do_genero) !== 1 ? 's' : '' ?> encontrado<?= count($mangas_do_genero) !== 1 ? 's' : '' ?>
            </p>

            <div class="grade-mangas">
                <?php foreach ($mangas_do_genero as $manga): ?>
                    <a href="manga.php?id=<?= $manga['id'] ?>" class="card-manga">
                        <div class="card-capa">
                            <?php if (!empty($manga['capa']) && file_exists($manga['capa'])): ?>
                                <img
                                    src="<?= sanitizar($manga['capa']) ?>"
                                    alt="Capa de <?= sanitizar($manga['titulo']) ?>"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="capa-placeholder">📚</div>
                            <?php endif; ?>

                            <span class="card-genero"><?= sanitizar($manga['genero']) ?></span>
                        </div>
                        <div class="card-info">
                            <h3 class="card-titulo"><?= sanitizar($manga['titulo']) ?></h3>
                            <?php if (!empty($manga['autor'])): ?>
                                <p class="card-autor"><?= sanitizar($manga['autor']) ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="estado-vazio">
                <span class="estado-vazio-icone">📦</span>
                <h3>Nenhum mangá neste gênero ainda</h3>
                <a href="generos.php" class="btn btn-secundario">Ver outros gêneros</a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- ===== LISTAGEM DE TODOS OS GÊNEROS ===== -->

        <h1 class="titulo-secao">🏷️ Explorar por Gênero</h1>
        <p style="color: var(--cinza-claro); margin-bottom: 32px;">
            Escolha um gênero para ver todos os mangás disponíveis nessa categoria.
        </p>

        <?php
        // Gêneros padrão mesmo que não existam no banco
        // Quando o banco estiver populado, esses serão substituídos pelos reais
        $generos_padrao = [
            ['genero' => 'Ação'],
            ['genero' => 'Romance'],
            ['genero' => 'Comédia'],
            ['genero' => 'Fantasia'],
            ['genero' => 'Isekai'],
            ['genero' => 'Shounen'],
            ['genero' => 'Seinen'],
            ['genero' => 'Drama'],
            ['genero' => 'Terror'],
            ['genero' => 'Slice of Life'],
        ];

        // Usar os do banco se existirem, senão usar os padrão
        $generos_exibir = !empty($todos_generos) ? $todos_generos : $generos_padrao;
        ?>

        <div class="grade-generos">
            <?php foreach ($generos_exibir as $genero_item): ?>
                <a href="generos.php?genero=<?= urlencode($genero_item['genero']) ?>" class="card-genero-item">
                    <span class="genero-icone">
                        <?= $icones_generos[$genero_item['genero']] ?? '📚' ?>
                    </span>
                    <span class="genero-nome"><?= sanitizar($genero_item['genero']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
