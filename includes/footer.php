<?php   
// Rodapé padrão do site.
// Incluo no final de cada página.
?>

<!-- ===== RODAPÉ ===== -->
<footer class="rodape">
    <div class="container">
        <div class="rodape-grade">

            <div class="rodape-coluna">
                <h3 class="logo-texto">⛩ MangaVerse</h3>
                <p>Seu portal de leitura de mangás favoritos. Projeto de estudo em PHP.</p>
            </div>

            <div class="rodape-coluna">
                <h4>Navegação</h4>
                <ul>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="busca.php">Buscar Mangás</a></li>
                    <li><a href="generos.php">Gêneros</a></li>
                </ul>
            </div>

            <div class="rodape-coluna">
                <h4>Conta</h4>
                <ul>
                    <li><a href="login.php">Entrar</a></li>
                    <li><a href="cadastro.php">Cadastrar</a></li>
                    <li><a href="favoritos.php">Favoritos</a></li>
                </ul>
            </div>

        </div>

        <div class="rodape-base">
            <p>© <?= date('Y') ?> MangaVerse — Projeto de estudo em PHP puro. Desenvolvido com ❤️</p>
        </div>
    </div>
</footer>

<!-- Botão "voltar ao topo" — o JavaScript em script.js controla quando ele aparece -->
<button class="btn-topo" id="btn-topo" aria-label="Voltar ao topo" title="Voltar ao topo">
    ↑
</button>

<!-- Arquivo JavaScript principal -->
<script src="assets/js/script.js"></script>
</body>
</html>
