<?php
// Permite pesquisar mangás por nome, gênero ou autor.
// Os resultados aparecem abaixo do formulário.
// Caso eu queira adicionar mais filtros no futuro
// (por exemplo, por ano ou nota), mexo na query SQL daqui.
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';
$titulo_pagina = 'Buscar Mangás';
// Pegar os termos de busca da URL (?nome=...&genero=...&autor=...)
// Uso GET para que o usuário possa compartilhar a URL com os resultados
$busca_nome   = trim($_GET['nome']   ?? '');
$busca_genero = trim($_GET['genero'] ?? '');
$busca_autor  = trim($_GET['autor']  ?? '');
$resultados    = [];
$buscou        = false; // Flag para saber se o usuário pesquisou algo
$total_resultados = 0;
// Só buscar se pelo menos um campo foi preenchido
$tem_busca = !empty($busca_nome) || !empty($busca_genero) || !empty($busca_autor);
if ($tem_busca) {
    $buscou = true;
    $condicoes = [];
    // Montar as condições SQL dinamicamente
    // Cada campo preenchido adiciona uma condição na busca

    if (!empty($busca_nome)) {
        // LIKE com % permite buscar palavras parciais
        // Ex: "naru" vai encontrar "Naruto"
        $nome_seguro = mysqli_real_escape_string($conexao, $busca_nome);
        $condicoes[] = "titulo LIKE '%$nome_seguro%'";
    }

    if (!empty($busca_genero)) {
        $genero_seguro = mysqli_real_escape_string($conexao, $busca_genero);
        $condicoes[] = "genero = '$genero_seguro'";
    }

    if (!empty($busca_autor)) {
        $autor_seguro = mysqli_real_escape_string($conexao, $busca_autor);
        $condicoes[] = "autor LIKE '%$autor_seguro%'";
    }

    // Juntar as condições com AND (todas devem ser verdadeiras)
    // Se quiser busca mais ampla (qualquer campo), trocar AND por OR
    $where = implode(' AND ', $condicoes);

    $sql = "SELECT * FROM mangas WHERE $where ORDER BY titulo ASC";
    $resultado_query = mysqli_query($conexao, $sql);
    $resultados = mysqli_fetch_all($resultado_query, MYSQLI_ASSOC);
    $total_resultados = count($resultados);
}

// Buscar todos os gêneros para o filtro de seleção
$todos_generos = buscarGeneros($conexao);

require_once 'includes/header.php';
?>

<div class="container" style="padding-top: 40px; padding-bottom: 40px;">

    <h1 class="titulo-secao">🔍 Buscar Mangás</h1>

    <!-- Formulário de busca -->
    <!-- Uso GET para que a busca fique na URL -->
    <form action="busca.php" method="GET">
        <div class="barra-busca">

            <!-- Campo: buscar por nome -->
            <input
                type="text"
                name="nome"
                placeholder="🔤 Buscar por nome..."
                value="<?= sanitizar($busca_nome) ?>"
                maxlength="200"
            >

            <!-- Campo: buscar por gênero (lista suspensa) -->
            <select name="genero">
                <option value="">🏷️ Todos os gêneros</option>
                <?php foreach ($todos_generos as $genero_item): ?>
                    <option
                        value="<?= sanitizar($genero_item['genero']) ?>"
                        <?= $busca_genero === $genero_item['genero'] ? 'selected' : '' ?>
                    >
                        <?= sanitizar($genero_item['genero']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Campo: buscar por autor -->
            <input
                type="text"
                name="autor"
                placeholder="✍️ Buscar por autor..."
                value="<?= sanitizar($busca_autor) ?>"
                maxlength="100"
            >

            <button type="submit" class="btn btn-primario">Pesquisar</button>

            <!-- Botão para limpar a busca -->
            <?php if ($tem_busca): ?>
                <a href="busca.php" class="btn btn-secundario">✕ Limpar</a>
            <?php endif; ?>

        </div>
    </form>

    <!-- Resultados da busca -->
    <?php if ($buscou): ?>

        <!-- Mostrar quantidade de resultados encontrados -->
        <p style="color: var(--cinza-claro); margin-bottom: 24px;">
            <?php if ($total_resultados > 0): ?>
                <strong><?= $total_resultados ?></strong> resultado<?= $total_resultados !== 1 ? 's' : '' ?> encontrado<?= $total_resultados !== 1 ? 's' : '' ?>
            <?php else: ?>
                Nenhum resultado encontrado. Tente outros termos.
            <?php endif; ?>
        </p>

        <?php if (!empty($resultados)): ?>
            <div class="grade-mangas">
                <?php foreach ($resultados as $manga): ?>
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

        <?php else: ?>
            <!-- Estado vazio — nenhum resultado -->
            <div class="estado-vazio">
                <span class="estado-vazio-icone">🔍</span>
                <h3>Nada encontrado</h3>
                <p>Tente buscar com termos diferentes ou navegue pelos gêneros.</p>
                <a href="generos.php" class="btn btn-secundario">Ver Gêneros</a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Estado inicial — antes de pesquisar -->
        <div class="estado-vazio">
            <span class="estado-vazio-icone">📖</span>
            <h3>Pesquise seus mangás</h3>
            <p>Use os campos acima para encontrar mangás por nome, gênero ou autor.</p>
        </div>
    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
