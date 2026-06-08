<?php
// Essa é a primeira página que o usuário vê.
// Exibe o banner, mangás em destaque, populares e gêneros.
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

$titulo_pagina = 'Início';

// Buscar mangás para exibir nas seções
// Se as listas estiverem vazias, verificar a conexão e o banco de dados
$mangas_recentes  = buscarMangas($conexao, 8);
$mangas_destaque  = buscarMangasDestaque($conexao);
$generos          = buscarGeneros($conexao);

// Ícones para os gêneros — adicionei manualmente, pode alterar à vontade
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

require_once 'includes/header.php';
?>

<!-- ==== BANNER PRINCIPAL ==== -->
<section class="banner">
    <div class="container">
        <div class="banner-conteudo">
            <h1>Seu Portal de<br>Mangás Favoritos</h1>
            <p>Explore centenas de títulos, salve seus favoritos e mergulhe em histórias incríveis de ação, romance, fantasia e muito mais.</p>
            <div class="banner-botoes">
                <a href="busca.php" class="btn btn-primario">🔍 Explorar Mangás</a>
                <?php if (!usuarioLogado()): ?>
                    <a href="cadastro.php" class="btn btn-secundario">✨ Criar Conta Grátis</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===== MANGÁS EM DESTAQUE ===== -->
<?php if (!empty($mangas_destaque)): ?>
<section class="secao">
    <div class="container">
        <h2 class="titulo-secao">⭐ Em Destaque</h2>
        <div class="grade-mangas">
            <?php foreach ($mangas_destaque as $manga): ?>
                <a href="manga.php?id=<?= $manga['id'] ?>" class="card-manga">
                    <div class="card-capa">
                        <?php if (!empty($manga['capa']) && file_exists($manga['capa'])): ?>
                            <img
                                src="<?= sanitizar($manga['capa']) ?>"
                                alt="Capa de <?= sanitizar($manga['titulo']) ?>"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <!-- Placeholder quando a imagem não existe ainda -->
                            <div class="capa-placeholder">📚</div>
                        <?php endif; ?>

                        <?php if (!empty($manga['genero'])): ?>
                            <span class="card-genero"><?= sanitizar($manga['genero']) ?></span>
                        <?php endif; ?>
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
    </div>
</section>
<?php endif; ?>

<!-- ===== ADICIONADOS RECENTEMENTE ===== -->
<?php if (!empty($mangas_recentes)): ?>
<section class="secao">
    <div class="container">
        <h2 class="titulo-secao">🆕 Adicionados Recentemente</h2>
        <div class="grade-mangas">
            <?php foreach ($mangas_recentes as $manga): ?>
                <a href="manga.php?id=<?= $manga['id'] ?>" class="card-manga">
                    <div class="card-capa">
                        <?php if (!empty($manga['capa']) && file_exists($manga['capa'])): ?>
                            <img
                                src="<?= sanitizar($manga['capa']) ?>"
                                alt="Capa de <?= sanitizar($manga['titulo']) ?>"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="capa-placeholder">📖</div>
                        <?php endif; ?>

                        <?php if (!empty($manga['genero'])): ?>
                            <span class="card-genero"><?= sanitizar($manga['genero']) ?></span>
                        <?php endif; ?>
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
        <div style="text-align:center; margin-top:10px;">
            <a href="busca.php" class="btn btn-secundario">Ver todos os mangás →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== LISTA DE GÊNEROS ===== -->
<?php if (!empty($generos)): ?>
<section class="secao">
    <div class="container">
        <h2 class="titulo-secao">🏷️ Explorar por Gênero</h2>
        <div class="grade-generos">
            <?php foreach ($generos as $genero_item): ?>
                <?php $nome_genero = $genero_item['genero']; ?>
                <a href="generos.php?genero=<?= urlencode($nome_genero) ?>" class="card-genero-item">
                    <span class="genero-icone">
                        <?= $icones_generos[$nome_genero] ?? '📚' ?>
                    </span>
                    <span class="genero-nome"><?= sanitizar($nome_genero) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Estado vazio — exibido se o banco estiver vazio -->
<?php if (empty($mangas_recentes) && empty($mangas_destaque)): ?>
<section class="secao">
    <div class="container">
        <div class="estado-vazio">
            <span class="estado-vazio-icone">📦</span>
            <h3>Nenhum mangá cadastrado ainda</h3>
            <p>Adicione mangás ao banco de dados para eles aparecerem aqui.</p>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
