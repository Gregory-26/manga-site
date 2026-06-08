
// JavaScript do MangaVerse.
// Aqui APENAS melhorias visuais e de experiência.
// O site funciona sem esse arquivo — ele só deixa mais bonito.
// Evitei frameworks (React, Vue, jQuery) de propósito.
// Quero entender o JavaScript puro primeiro
// FADE AO CARREGAR A PÁGINA
// Esse efeito suaviza a entrada na página.
// O CSS começa com opacity: 0 e aqui adiciono a classe "carregado"
// que muda para opacity: 1 com transição suave.

document.addEventListener('DOMContentLoaded', function() {
    // Pequeno atraso para garantir que o CSS carregou antes
    setTimeout(function() {
        document.body.classList.add('carregado');
    }, 50);
});
// MENU MOBILE
// Quando o usuário clicar no ícone de "hamburguer",
// o menu abre e fecha.
// Funciona adicionando/removendo a classe "aberto" no menu.

var btnMenuMobile = document.getElementById('btn-menu-mobile');
var menu = document.getElementById('menu');

if (btnMenuMobile && menu) {
    btnMenuMobile.addEventListener('click', function() {

        // Alternar (toggle) a classe "aberto"
        menu.classList.toggle('aberto');

        // Alterar o aria-label para acessibilidade
        var estaAberto = menu.classList.contains('aberto');
        btnMenuMobile.setAttribute('aria-label', estaAberto ? 'Fechar menu' : 'Abrir menu');

        // Animação nas linhas do botão hamburguer
        // Isso transforma as 3 linhas em um X quando aberto
        var linhas = btnMenuMobile.querySelectorAll('span');
        if (estaAberto) {
            linhas[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
            linhas[1].style.opacity = '0';
            linhas[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
        } else {
            linhas[0].style.transform = '';
            linhas[1].style.opacity = '';
            linhas[2].style.transform = '';
        }
    });

    // Fechar o menu se clicar fora dele
    document.addEventListener('click', function(event) {
        var clicouFora = !menu.contains(event.target) && !btnMenuMobile.contains(event.target);
        if (clicouFora && menu.classList.contains('aberto')) {
            menu.classList.remove('aberto');
            var linhas = btnMenuMobile.querySelectorAll('span');
            linhas[0].style.transform = '';
            linhas[1].style.opacity = '';
            linhas[2].style.transform = '';
        }
    });
}

// BOTÃO VOLTAR AO TOPO
// Aparece quando o usuário rola a página para baixo.
// Quando clicado, sobe suavemente para o topo.
// O scroll suave está no CSS: html { scroll-behavior: smooth }

var btnTopo = document.getElementById('btn-topo');

if (btnTopo) {
    // Monitorar o scroll da página
    window.addEventListener('scroll', function() {
        // Mostrar o botão após rolar 400px
        if (window.scrollY > 400) {
            btnTopo.classList.add('visivel');
        } else {
            btnTopo.classList.remove('visivel');
        }
    });

    // Ao clicar, voltar ao topo
    btnTopo.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}
// ANIMAÇÃO NOS CARDS AO ENTRAR NA TELA
// Esse efeito deixa os cards mais vivos.
// Não é obrigatório para o sistema funcionar,
// serve apenas para melhorar a aparência.
//
// Uso o IntersectionObserver para detectar quando
// o elemento entra na área visível da tela.

function animarCardsAoVer() {
    // Selecionar todos os cards da página
    var cards = document.querySelectorAll('.card-manga, .card-genero-item');

    if (cards.length === 0) return; // Se não tem cards, sair

    // Configurar o observer
    var observer = new IntersectionObserver(function(entradas) {
        entradas.forEach(function(entrada) {
            if (entrada.isIntersecting) {
                // Adicionar classe de animação quando o card aparecer
                entrada.target.style.opacity = '1';
                entrada.target.style.transform = 'translateY(0)';
                // Parar de observar esse card (já foi animado)
                observer.unobserve(entrada.target);
            }
        });
    }, {
        threshold: 0.1, // Ativar quando 10% do card estiver visível
        rootMargin: '0px 0px -30px 0px'
    });

    // Preparar cada card para a animação
    cards.forEach(function(card, indice) {
        // Começar invisível e um pouco abaixo
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        // Transição suave
        card.style.transition = 'opacity 0.5s ease ' + (indice * 0.05) + 's, transform 0.5s ease ' + (indice * 0.05) + 's';
        // Pedir ao observer para monitorar esse card
        observer.observe(card);
    });
}

// Chamar a função quando a página terminar de carregar
document.addEventListener('DOMContentLoaded', animarCardsAoVer);

// CONFIRMAÇÃO AO REMOVER FAVORITO
// Antes de remover um favorito, pedir confirmação ao usuário.
// Isso evita remoções acidentais.

var btnRemoverFavorito = document.querySelectorAll('.btn-remover-favorito');

btnRemoverFavorito.forEach(function(btn) {
    btn.addEventListener('click', function(event) {
        var confirmar = window.confirm('Deseja remover este mangá dos seus favoritos?');
        if (!confirmar) {
            event.preventDefault(); // Cancelar a ação se o usuário disser "não"
        }
    });
});

// FEEDBACK VISUAL NOS BOTÕES DE FORMULÁRIO
// Quando o usuário clica em "Entrar" ou "Cadastrar",
// mostrar um estado de "carregando" para indicar que está processando.

var formularios = document.querySelectorAll('form');

formularios.forEach(function(form) {
    form.addEventListener('submit', function() {
        var botaoSubmit = form.querySelector('button[type="submit"]');
        if (botaoSubmit) {
            // Deixar o botão desabilitado e mudar o texto
            // Isso evita que o usuário clique duas vezes
            botaoSubmit.disabled = true;
            botaoSubmit.style.opacity = '0.7';
            var textoOriginal = botaoSubmit.textContent;
            botaoSubmit.textContent = 'Aguarde...';

            // Restaurar depois de 5 segundos (caso algo dê errado no servidor)
            setTimeout(function() {
                botaoSubmit.disabled = false;
                botaoSubmit.style.opacity = '';
                botaoSubmit.textContent = textoOriginal;
            }, 5000);
        }
    });
});

// HIGHLIGHT DO LINK ATIVO NO MENU
// Sublinhar o link da página atual no menu.
// Compara a URL atual com o href de cada link.

var linksMenu = document.querySelectorAll('.menu a');
var urlAtual = window.location.pathname.split('/').pop(); // Ex: "index.php"

linksMenu.forEach(function(link) {
    var hrefLink = link.getAttribute('href');
    if (hrefLink === urlAtual || (urlAtual === '' && hrefLink === 'index.php')) {
        link.style.color = '#b026ff';
        link.style.background = 'rgba(138,43,226,0.15)';
    }
});
