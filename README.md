
## Olá, eu sou o Gregory!

Desenvolvedor em construção, apaixonado por mangás, tecnologia e por transformar ideias malucas em código funcional. Esse projeto nasceu de uma mistura explosiva de curiosidade, vontade de aprender PHP do zero e uma pergunta simples que faço pra mim mesmo toda vez que abro o computador: **"e se eu construísse isso?"**
Spoiler: funcionou.

---

## O que é o MangaVerse?

O **MangaVerse** é uma plataforma completa de leitura de mangás desenvolvida do zero em **PHP puro**, sem frameworks, sem mágica, sem atalhos — só código, lógica e muita determinação.

A ideia é simples e direta: um lugar onde você entra, escolhe seu mangá favorito, e lê. Sem anúncio pulando na sua cara, sem cadastro obrigatório pra ver o básico, sem aquele loading eterno que parece que o servidor foi a pé buscar as imagens no Japão.

É um projeto de estudo, mas feito com o mesmo cuidado de quem vai lançar no ar amanhã. Porque aprender pela metade não é aprender — é enrolar.

---

## Tecnologias utilizadas

Aqui vai a lista honesta do que foi usado, sem inventar stack bonita pra impressionar ninguém:

| Tecnologia | Pra que serve aqui |
|---|---|
| **PHP** | Toda a lógica do servidor, autenticação, consultas ao banco |
| **MySQL** | Banco de dados com 4 tabelas bem estruturadas |
| **HTML5** | Estrutura de todas as páginas |
| **CSS3** | Visual dark temático com variáveis, responsividade e efeitos hover |
| **JavaScript puro** | Animações, menu mobile, scroll suave — sem jQuery, sem React, sem muleta |
| **Git + GitHub** | Versionamento e repositório público |

Sim, **zero frameworks**. PHP procedural na veia. Pra quem acha que isso é coisa do passado: o passado funcionava muito bem, obrigado.

---

##  O que o projeto tem

-  **Página inicial** com mangás em destaque, recentes e lista de gêneros
-  **Busca** por nome, gênero e autor
- **Página de gêneros** com categorias como Ação, Isekai, Romance, Terror e mais
-  **Leitor de capítulos** com navegação entre páginas e suporte a PDF
-  **Sistema de favoritos** para usuários logados
-  **Cadastro e login** com senha criptografada via `password_hash()`
-  **Perfil do usuário** com estatísticas
-  **Layout 100% responsivo** — funciona no celular sem choro

---

## 🗄️ Estrutura do banco de dados

Quatro tabelas, simples e eficientes:

```
usuarios   → quem usa o site
mangas     → os títulos cadastrados
capitulos  → capítulos de cada mangá com caminho das imagens
favoritos  → relação entre usuário e mangá favorito
```

---

## 📁 Estrutura de pastas

```
manga-site/
├── index.php
├── login.php
├── cadastro.php
├── logout.php
├── perfil.php
├── favoritos.php
├── busca.php
├── generos.php
├── manga.php
├── capitulo.php
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   └── imagens/
├── includes/
│   ├── conexao.php
│   ├── header.php
│   ├── footer.php
│   └── funcoes.php
└── banco/
    └── database.sql
```

---

## 🚀 Como rodar o projeto localmente

**Pré-requisitos:** XAMPP, Laragon ou qualquer servidor com PHP 7.4+ e MySQL.

```bash
# 1. Clone o repositório
git clone https://github.com/gregory/manga-site.git

# 2. Coloque a pasta dentro do htdocs (XAMPP) ou www (Laragon)

# 3. Importe o banco de dados
# Abra o phpMyAdmin e importe o arquivo banco/database.sql

# 4. Configure a conexão
# Edite includes/conexao.php com seu usuário e senha do MySQL

# 5. Acesse no navegador
http://localhost/manga-site
```

Pronto. Sem `npm install`, sem `composer update`, sem rezar pra funcionar. É PHP — você coloca no servidor e roda. Clássico.

---

## 🔮 Melhorias futuras (a lista que nunca acaba)

Esse projeto está vivo e tem muito chão pela frente. Aqui o que já está no radar:

- [ ] **Painel administrativo** — cadastrar mangás, capítulos e capas direto pelo site sem precisar abrir o phpMyAdmin
- [ ] **Sistema de avaliação** — dar nota e deixar comentário nos capítulos
- [ ] **Histórico de leitura** — marcar automaticamente qual capítulo o usuário parou
- [ ] **Modo claro** — porque tem gente que lê com a luz acesa (sem julgamento)
- [ ] **Upload de capítulos em PDF** — enviar o PDF e o próprio site converte em imagens
- [ ] **Notificações de novos capítulos** — avisar quando sair capítulo novo de um mangá favoritado
- [ ] **Busca avançada** — filtrar por status (em andamento, finalizado), por número de capítulos, por popularidade
- [ ] **PWA (Progressive Web App)** — instalar o site como aplicativo no celular
- [ ] **API REST** — transformar o backend em uma API pra um futuro app mobile
- [ ] **Migração para PDO** — substituir as queries com mysqli por PDO para maior segurança e flexibilidade

## O que aprendi construindo isso

Quando comecei esse projeto eu sabia o básico. Quando terminei a primeira versão eu entendia na prática como funciona:

- Autenticação segura com hash de senha
- Prevenção de SQL Injection com escape de dados
- Sessões PHP e controle de acesso por página
- Cinemática Inversa e FABRIK *(sim, teve um escorpião animado no meio dessa jornada)*
- Como converter PDF em imagens via linha de comando
- Git, GitHub e o famoso ciclo `add → commit → push`
- Que comentar o código não é frescura — é respeito com o eu do futuro
## Contribuições

Encontrou um bug? Tem uma ideia boa? Abre uma issue ou manda um pull request! Esse projeto é aberto justamente pra isso — aprender junto é melhor do que aprender sozinho.


## Licença

Projeto de uso livre para fins de estudo. Usa à vontade, modifica, melhora — só não fala que fez sozinho. 

---

<div align="center">

Feito com muito `git push --force` por **Gregory**
</div>
