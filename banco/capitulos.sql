
-- Rode esse arquivo no phpMyAdmin para adicionar o Naruto
-- com a capa e o capítulo 694 ao site.
-- Como usar:
-- 1. Abra o phpMyAdmin
-- 2. Selecione o banco "manga_site"
-- 3. Clique em "Importar" (ou aba "SQL")
-- 4. Cole esse conteúdo e clique em "Executar"
USE manga_site;
-- PASSO 1: Inserir (ou atualizar) o Naruto na tabela mangas
-- O INSERT ... ON DUPLICATE KEY UPDATE serve pra:
-- - Se o Naruto ainda NÃO existe: cria o registro
-- - Se o Naruto JÁ existe com esse título: só atualiza a capa
-- Se preferir inserir direto sem essa lógica, use apenas o INSERT.
INSERT INTO mangas (titulo, descricao, genero, autor, capa, destaque)
VALUES (
    'Naruto',
    'A história de Naruto Uzumaki, um jovem ninja que carrega a Raposa de Nove Caudas selada em seu corpo. Desprezado pela vila de Konoha, ele sonha em se tornar Hokage — o ninja mais forte — para conquistar o reconhecimento de todos. Ao longo de sua jornada, ele forma laços profundos, enfrenta inimigos poderosos e descobre a verdade sobre seu passado.',
    'Ação',
    'Masashi Kishimoto',
    'assets/imagens/naruto.jpg',
    1
);

-- Guarda o ID do Naruto que acabou de ser inserido
-- Se você já tinha um Naruto no banco, use o ID correto abaixo
SET @naruto_id = LAST_INSERT_ID();
-- PASSO 2: Inserir o Capítulo 694
-- manga_id  = ID do Naruto (capturado acima)
-- numero    = 694
-- titulo    = nome do capítulo
-- pasta_imagens = onde estão os JPGs gerados pelo script
-- IMPORTANTE: o caminho da pasta deve ser relativo à raiz
-- do projeto (onde fica o index.php).
INSERT INTO capitulos (manga_id, numero, titulo, pasta_imagens)
VALUES (
    @naruto_id,
    694,
    'Naruto e Sasuke',
    'assets/imagens/capitulos/naruto/cap694/'
);
-- Verificação final: checar se foi inserido corretamente
SELECT
    m.id        AS manga_id,
    m.titulo    AS manga,
    m.capa,
    c.id        AS capitulo_id,
    c.numero    AS capitulo_numero,
    c.titulo    AS capitulo_titulo,
    c.pasta_imagens
FROM mangas m
JOIN capitulos c ON c.manga_id = m.id
WHERE m.titulo = 'Naruto'
ORDER BY c.numero ASC;
