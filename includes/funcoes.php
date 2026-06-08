<?php
// includes/funcoes.php 
// Aqui ficam as funções que eu uso em várias páginas do site.
// Em vez de repetir o mesmo código em todo lugar,
// coloco a lógica aqui e chamo quando precisar
// Se eu quiser mudar como o login funciona, por exemplo,
// mudo APENAS aqui, e todas as páginas que usam essa função
// serão atualizadas automaticamente.
// Iniciar sessão se ainda não estiver ativa.
// session_start() precisa ser chamado antes de usar $_SESSION.
// Coloquei aqui para não precisar repetir em cada página.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// FUNÇÃO: usuarioLogado()
// Verifica se tem alguém logado no site.
// Retorna true (logado) ou false (não logado).
// Uso: if (usuarioLogado()) { ... }
function usuarioLogado() {
    // $_SESSION['usuario_id'] é definida na página de login.
    // Se ela existir, significa que o usuário está logado.
    return isset($_SESSION['usuario_id']);
}     
// FUNÇÃO: redirecionar($pagina)
// Redireciona o usuário para outra página.
// Útil para enviar para login quando não está logado,
// ou para a home depois de cadastrar.
// Uso: redirecionar('login.php');
function redirecionar($pagina) {
    header("Location: $pagina");
    exit; // Para o script aqui — importante para o header funcionar
}
// FUNÇÃO: buscarMangas($conexao, $limite)
// Busca os mangás no banco de dados.
// Uso: $mangas = buscarMangas($conexao, 8);
// Se eu quiser adicionar filtros (por gênero, por nome, etc.)
// vou mexer nessa função futuramente.
function buscarMangas($conexao, $limite = 12) {
    // Lembrete para mim:
    // Se essa variável estiver vazia,
    // verificar primeiro a conexão com o banco.
    $sql = "SELECT * FROM mangas ORDER BY criado_em DESC LIMIT $limite";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
// FUNÇÃO: buscarMangasDestaque($conexao)
// Busca apenas os mangás marcados como destaque (destaque = 1).
function buscarMangasDestaque($conexao) {
    $sql = "SELECT * FROM mangas WHERE destaque = 1 ORDER BY criado_em DESC LIMIT 6";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
} 
// FUNÇÃO: buscarMangaPorId($conexao, $id)
// Busca um mangá específico pelo ID.
// Uso: $manga = buscarMangaPorId($conexao, 3);              
function buscarMangaPorId($conexao, $id) {
    // Converter para inteiro para evitar SQL Injection
    $id = (int) $id;
    $sql = "SELECT * FROM mangas WHERE id = $id";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($resultado);
}
// FUNÇÃO: buscarCapitulosPorManga($conexao, $manga_id)
// Retorna todos os capítulos de um mangá em ordem crescente. 
function buscarCapitulosPorManga($conexao, $manga_id) {
    $manga_id = (int) $manga_id;
    $sql = "SELECT * FROM capitulos WHERE manga_id = $manga_id ORDER BY numero ASC";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
// FUNÇÃO: buscarCapituloPorId($conexao, $id)
// Retorna os dados de um capítulo específico.
function buscarCapituloPorId($conexao, $id) {
    $id = (int) $id;
    $sql = "SELECT * FROM capitulos WHERE id = $id";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($resultado);
}
// FUNÇÃO: ehFavorito($conexao, $usuario_id, $manga_id)
// Verifica se um mangá já está nos favoritos do usuário.
//Retorna true ou false.
function ehFavorito($conexao, $usuario_id, $manga_id) {
    $usuario_id = (int) $usuario_id;
    $manga_id   = (int) $manga_id;
    $sql = "SELECT id FROM favoritos WHERE usuario_id = $usuario_id AND manga_id = $manga_id";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_num_rows($resultado) > 0;
}
// FUNÇÃO: buscarFavoritos($conexao, $usuario_id)
// Retorna todos os mangás favoritados pelo usuário.
// Usa JOIN para pegar as informações do mangá junto. 
function buscarFavoritos($conexao, $usuario_id) {
    $usuario_id = (int) $usuario_id;
    $sql = "
        SELECT m.*
        FROM favoritos f
        JOIN mangas m ON f.manga_id = m.id
        WHERE f.usuario_id = $usuario_id
        ORDER BY f.salvo_em DESC
    ";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
} 
// FUNÇÃO: buscarUsuarioPorId($conexao, $id)
// Retorna os dados do usuário logado.  
function buscarUsuarioPorId($conexao, $id) {
    $id = (int) $id;
    $sql = "SELECT id, nome, usuario, email, data_cadastro FROM usuarios WHERE id = $id";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($resultado);
}
// FUNÇÃO: contarFavoritosDoUsuario($conexao, $usuario_id)
// Conta quantos favoritos o usuário tem.
// Uso na página de perfil para exibir essa informação.
function contarFavoritosDoUsuario($conexao, $usuario_id) {
    $usuario_id = (int) $usuario_id;
    $sql = "SELECT COUNT(*) as total FROM favoritos WHERE usuario_id = $usuario_id";
    $resultado = mysqli_query($conexao, $sql);
    $linha = mysqli_fetch_assoc($resultado);
    return $linha['total'];
}
// FUNÇÃO: buscarGeneros($conexao)
// Retorna todos os gêneros únicos cadastrados nos mangás.
function buscarGeneros($conexao) {
    $sql = "SELECT DISTINCT genero FROM mangas WHERE genero IS NOT NULL ORDER BY genero ASC";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
// FUNÇÃO: buscarMangasPorGenero($conexao, $genero)
// Busca mangás de um gênero específico.
function buscarMangasPorGenero($conexao, $genero) {
    // Escapar o valor para evitar SQL Injection
    $genero = mysqli_real_escape_string($conexao, $genero);
    $sql = "SELECT * FROM mangas WHERE genero = '$genero' ORDER BY titulo ASC";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
// FUNÇÃO: sanitizar($texto)
// Limpa um texto para exibir com segurança no HTML.
// Evita que alguém injete código HTML malicioso na página.
// Sempre usar essa função ao mostrar dados vindos do usuário!
function sanitizar($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
