<?php
// Lista os mangás salvos pelo usuário logado.
// Também permite remover um mangá dos favoritos.
// Também permite adicionar um favorito (via POST).
// Só pode ser acessada por usuários logados.
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

// Verificar se o usuário está logado
if (!usuarioLogado()) {
    // Salvar a URL atual para redirecionar de volta após o login
    redirecionar('login.php');
}

$usuario_id = (int) $_SESSION['usuario_id'];
$mensagem = '';
$tipo_mensagem = '';

// Processar ação de adicionar/remover favorito
// Recebo via POST para não expor a ação na URL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao     = $_POST['acao']     ?? '';
    $manga_id = (int) ($_POST['manga_id'] ?? 0);

    if ($manga_id > 0) {

        if ($acao === 'adicionar') {
            // Tentar adicionar. O IGNORE evita erro se já existir (UNIQUE KEY)
            $sql = "INSERT IGNORE INTO favoritos (usuario_id, manga_id) VALUES ($usuario_id, $manga_id)";
            mysqli_query($conexao, $sql);
            $mensagem = 'Mangá adicionado aos favoritos! ❤️';
            $tipo_mensagem = 'sucesso';

        } elseif ($acao === 'remover') {
            $sql = "DELETE FROM favoritos WHERE usuario_id = $usuario_id AND manga_id = $manga_id";
            mysqli_query($conexao, $sql);
            $mensagem = 'Mangá removido dos favoritos.';
            $tipo_mensagem = 'erro';
        }
    }

    // Após processar, redirecionar para evitar reenvio do formulário
    // O "?msg=..." passa a mensagem para a próxima requisição
    redirecionar('favoritos.php');
}

// Buscar os favoritos do usuário
// Lembrete para mim:
// Se essa lista estiver vazia mesmo com favoritos no banco,
// verificar a query na função buscarFavoritos() em funcoes.php
$favoritos = buscarFavoritos($conexao, $usuario_id);

$titulo_pagina = 'Meus Favoritos';
require_once 'includes/header.php';
?>

<div class="container" style="padding-top: 40px; padding-bottom: 40px;">

    <h1 class="titulo-secao">❤️ Meus Favoritos</h1>

    <?php if (!empty($mensagem)): ?>
        <div class="mensagem mensagem-<?= $tipo_mensagem ?>">
            <?= sanitizar($mensagem) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($favoritos)): ?>
        <!-- Estado vazio — quando não tem nenhum favorito ainda -->
        <div class="estado-vazio">
            <span class="estado-vazio-icone">💔</span>
            <h3>Nenhum favorito ainda</h3>
            <p>Explore os mangás e clique em "Favoritar" para salvá-los aqui.</p>
            <a href="busca.php" class="btn btn-primario">🔍 Explorar Mangás</a>
        </div>

    <?php else: ?>
        <!-- Lista de mangás favoritos -->
        <div class="grade-mangas">
            <?php foreach ($favoritos as $manga): ?>
                <div class="card-manga" style="position:relative;">

                    <!-- Link para o mangá -->
                    <a href="manga.php?id=<?= $manga['id'] ?>" style="display:block; color:inherit; text-decoration:none;">
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

                    <!-- Botão de remover dos favoritos -->
                    <div style="padding: 0 14px 14px;">
                        <form action="favoritos.php" method="POST">
                            <input type="hidden" name="acao"     value="remover">
                            <input type="hidden" name="manga_id" value="<?= $manga['id'] ?>">
                            <button
                                type="submit"
                                class="btn btn-perigo btn-remover-favorito"
                                style="width:100%; justify-content:center; padding: 8px;"
                            >
                                🗑️ Remover
                            </button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <p style="text-align:center; color: var(--cinza-claro); font-size: 0.9rem;">
            <?= count($favoritos) ?> mangá<?= count($favoritos) !== 1 ? 's' : '' ?> na sua lista
        </p>

    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
