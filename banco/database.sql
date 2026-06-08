-
-- Aqui ficam todas as tabelas que o site vai usar.
-- Rode esse arquivo no phpMyAdmin ou pelo terminal MySQL.
-- Se algo der errado, verificar se o banco "manga_site" já existe.

CREATE DATABASE IF NOT EXISTS manga_site
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Selecionar o banco para usar
USE manga_site;
-- TABELA: usuarios
-- Guarda os dados de quem se cadastrou no site.
-- A senha NUNCA é salva como texto puro — usamos hash.

CREATE TABLE IF NOT EXISTS usuarios (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(100)  NOT NULL,
    usuario       VARCHAR(50)   NOT NULL UNIQUE,
    email         VARCHAR(150)  NOT NULL UNIQUE,
    senha         VARCHAR(255)  NOT NULL,    -- Hash gerado pelo password_hash()
    data_cadastro DATETIME      DEFAULT CURRENT_TIMESTAMP
);

-- TABELA: mangas
-- Guarda as informações de cada mangá cadastrado.
-- O campo "capa" guarda o caminho da imagem (ex: assets/imagens/capa.jpg)

CREATE TABLE IF NOT EXISTS mangas (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    titulo    VARCHAR(200)  NOT NULL,
    descricao TEXT,
    genero    VARCHAR(100),
    autor     VARCHAR(100),
    capa      VARCHAR(300),                  -- Caminho relativo da imagem de capa
    destaque  TINYINT(1)   DEFAULT 0,        -- 1 = em destaque, 0 = normal
    criado_em DATETIME     DEFAULT CURRENT_TIMESTAMP
);

--  
-- TABELA: capitulos
-- Cada capítulo pertence a um mangá (manga_id).
-- "pasta_imagens" é o caminho onde ficam as imagens do capítulo.
--  
CREATE TABLE IF NOT EXISTS capitulos (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    manga_id       INT          NOT NULL,
    numero         FLOAT        NOT NULL,    -- Permite capítulos como 10.5
    titulo         VARCHAR(200),
    pasta_imagens  VARCHAR(300),             -- Ex: assets/imagens/manga1/capitulo1/
    criado_em      DATETIME     DEFAULT CURRENT_TIMESTAMP,

    -- Relaciona com a tabela mangas
    FOREIGN KEY (manga_id) REFERENCES mangas(id) ON DELETE CASCADE
);

--  
-- TABELA: favoritos
-- Relaciona um usuário com um mangá que ele favoritou.
-- Cada par usuario_id + manga_id é único (não duplica favorito).
--  
CREATE TABLE IF NOT EXISTS favoritos (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    manga_id   INT NOT NULL,
    salvo_em   DATETIME DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY favorito_unico (usuario_id, manga_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (manga_id)   REFERENCES mangas(id)   ON DELETE CASCADE
);

--  
-- DADOS DE EXEMPLO
-- Inserindo alguns mangás para o site não ficar vazio.
-- Depois que o sistema estiver funcionando, remover esses dados
-- e cadastrar os mangás reais pelo painel ou banco.
--  

INSERT INTO mangas (titulo, descricao, genero, autor, capa, destaque) VALUES
('Naruto', 'A história de Naruto Uzumaki, um jovem ninja que busca reconhecimento de seus companheiros e sonha em se tornar Hokage, o líder de sua vila.', 'Ação', 'Masashi Kishimoto', 'assets/imagens/naruto.jpg', 1),
('One Piece', 'Monkey D. Luffy e seus amigos piratas buscam o lendário tesouro One Piece para que Luffy se torne o Rei dos Piratas.', 'Ação', 'Eiichiro Oda', 'assets/imagens/onepiece.jpg', 1),
('Sword Art Online', 'Kirito fica preso em um jogo de realidade virtual e precisa chegar ao último andar para escapar.', 'Isekai', 'Reki Kawahara', 'assets/imagens/sao.jpg', 0),
('Your Lie in April', 'Um prodígio do piano que perdeu a capacidade de ouvir suas próprias notas encontra uma violinista que muda sua vida.', 'Romance', 'Naoshi Arakawa', 'assets/imagens/yourlie.jpg', 0),
('Attack on Titan', 'Numa humanidade cercada por gigantes comedores de gente, Eren Yeager jura se vingar após uma tragédia pessoal.', 'Drama', 'Hajime Isayama', 'assets/imagens/aot.jpg', 1),
('Spy x Family', 'Um espião, uma assassina e uma telepata formam uma família falsa — sem saber os segredos um do outro.', 'Comédia', 'Tatsuya Endo', 'assets/imagens/spyfamily.jpg', 0),
('Demon Slayer', 'Tanjiro Kamado se torna um Caçador de Demônios para salvar sua irmã transformada em demônio.', 'Ação', 'Koyoharu Gotouge', 'assets/imagens/demonslayer.jpg', 1),
('Overlord', 'Um jogador fica preso em um jogo como seu avatar de esqueleto supremo e tenta descobrir se há outros humanos nesse novo mundo.', 'Isekai', 'Kugane Maruyama', 'assets/imagens/overlord.jpg', 0);

-- Capítulos de exemplo para o Naruto
INSERT INTO capitulos (manga_id, numero, titulo, pasta_imagens) VALUES
(1, 1,  'Entre em Cena: Uzumaki Naruto!!', 'assets/imagens/capitulos/naruto/cap1/'),
(1, 2,  'Konohamaru!!', 'assets/imagens/capitulos/naruto/cap2/'),
(1, 3,  'Sasuke Uchiha!!', 'assets/imagens/capitulos/naruto/cap3/');

-- Capítulos de exemplo para One Piece
INSERT INTO capitulos (manga_id, numero, titulo, pasta_imagens) VALUES
(2, 1,  'Romance Dawn — O Alvorecer da Aventura', 'assets/imagens/capitulos/onepiece/cap1/'),
(2, 2,  'Que tipo de vontade herdamos', 'assets/imagens/capitulos/onepiece/cap2/');
