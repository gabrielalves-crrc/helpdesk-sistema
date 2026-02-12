document.addEventListener('click', function (e) {
    if (e.target.classList.contains('toggle-desc')) {
        const text = e.target.previousElementSibling;

        text.classList.toggle('expanded');

        e.target.textContent =
            text.classList.contains('expanded') ? 'Ver menos' : 'Ver mais';
    }
});

const darkBtn = document.getElementById('toggleDark');

if (darkBtn) {
    // carrega preferência
    if (localStorage.getItem('darkmode') === 'on') {
        document.body.classList.add('dark');
        darkBtn.textContent = '☀️';
    }

    darkBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark');

        if (document.body.classList.contains('dark')) {
            localStorage.setItem('darkmode', 'on');
            darkBtn.textContent = '☀️';
        } else {
            localStorage.setItem('darkmode', 'off');
            darkBtn.textContent = '🌙';
        }
    });
}


window.addEventListener("load", () => {
    const skeleton = document.getElementById("skeleton-container");
    const tickets = document.getElementById("tickets-container");

    setTimeout(() => {
        skeleton.style.display = "none";
        tickets.classList.remove("hidden");
    }, 500);
});

const searchInput = document.getElementById('searchInput');
const tickets = document.querySelectorAll('.ticket-card');

if (searchInput) {
    searchInput.addEventListener('input', () => {
        const termo = searchInput.value.toLowerCase();

        tickets.forEach(ticket => {
            const titulo = ticket.dataset.titulo;
            const usuario = ticket.dataset.usuario;

            const match =
                titulo.includes(termo) ||
                usuario.includes(termo);

            if (match) {
                ticket.classList.remove('hidden');
            } else {
                ticket.classList.add('hidden');
            }
        });
    });
}

// ===== LEIA MAIS PARA DESCRIÇÕES LONGS =====
function toggleDescricao(id) {
    const descricao = document.getElementById('desc-' + id);
    const botao = document.getElementById('btn-' + id);

    if (descricao.classList.contains('expandido')) {
        // Volta para o tamanho normal
        descricao.classList.remove('expandido');
        descricao.classList.add('cortado');
        botao.textContent = 'Leia mais...';
    } else {
        // Expande completamente
        descricao.classList.add('expandido');
        descricao.classList.remove('cortado');
        botao.textContent = 'Mostrar menos';
    }
}

// Verifica quais textos precisam do botão "Leia mais"
document.addEventListener('DOMContentLoaded', function () {
    const descricoes = document.querySelectorAll('.desc-text');

    descricoes.forEach(desc => {
        // Se a altura natural for maior que 100px, precisa do botão
        if (desc.scrollHeight > 100) {
            desc.classList.add('cortado');
            const id = desc.id.replace('desc-', '');
            const botao = document.getElementById('btn-' + id);
            if (botao) {
                botao.style.display = 'inline-block';
            }
        }
    });
});

// ===== TOGGLE HISTÓRICO COMPLETO =====
function toggleHistorico(ticketId) {
    const historico = document.getElementById('historico-' + ticketId);
    const botao = document.getElementById('btn-historico-' + ticketId);

    if (historico.style.display === 'none') {
        // Mostra histórico completo
        historico.style.display = 'block';
        botao.textContent = 'Ocultar histórico';
    } else {
        // Esconde histórico completo
        historico.style.display = 'none';
        botao.textContent = 'Ver histórico completo';
    }
}

// ===== BOTÃO VOLTAR AO TOPO (GARANTIDO) =====
document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTop');
    
    if (!backToTopBtn) {
        console.error('❌ Botão #backToTop não encontrado!');
        return;
    }
    
    // Mostra/oculta ao rolar
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    });
    
    // Clique para voltar ao topo
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Forçar mostrar por 5 segundos para teste
    setTimeout(() => {
        backToTopBtn.classList.add('show');
        backToTopBtn.style.background = 'red';
        
        setTimeout(() => {
            backToTopBtn.style.background = '';
            if (window.scrollY <= 300) {
                backToTopBtn.classList.remove('show');
            }
        }, 5000);
    }, 1000);
});

// Função para minimizar/expandir chamado
function toggleTicket(ticketId) {
    const ticket = document.getElementById(`ticket-${ticketId}`);
    const btn = ticket.querySelector('.minimize-btn i');
    
    // Alternar classe 'minimized'
    ticket.classList.toggle('minimized');
    
    // Alternar ícone
    if (ticket.classList.contains('minimized')) {
        btn.className = 'fas fa-plus';
        btn.title = 'Expandir';
        // Salvar estado
        localStorage.setItem(`ticket-${ticketId}-minimized`, 'true');
    } else {
        btn.className = 'fas fa-minus';
        btn.title = 'Minimizar';
        // Remover estado
        localStorage.removeItem(`ticket-${ticketId}-minimized`);
    }
}



// Restaurar estados minimizados ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    // Para cada chamado na página
    document.querySelectorAll('.ticket-card').forEach(ticket => {
        const ticketId = ticket.id.replace('ticket-', '');
        const isMinimized = localStorage.getItem(`ticket-${ticketId}-minimized`) === 'true';
        
        if (isMinimized) {
            ticket.classList.add('minimized');
            const btn = ticket.querySelector('.minimize-btn i');
            if (btn) {
                btn.className = 'fas fa-plus';
                btn.title = 'Expandir';
            }
        }
    });
});

// Toggle para Status - Versão Otimizada
const statusToggle = document.getElementById('statusToggle');
const statusContent = document.getElementById('statusContent');
const statusContainer = document.querySelector('.status-toggle-container');

if (statusToggle) {
    statusToggle.addEventListener('click', function(e) {
        e.stopPropagation(); // Previne propagação
        
        const isOpen = statusContainer.classList.contains('open');
        
        if (isOpen) {
            // Fechar com animação
            statusContent.style.transition = 'max-height 0.3s ease-in-out, opacity 0.2s ease 0.1s, padding 0.3s ease';
            statusContainer.classList.remove('open');
            statusToggle.setAttribute('aria-expanded', 'false');
            
            // Animar ícone
            const icon = this.querySelector('.toggle-icon');
            icon.style.transition = 'transform 0.3s ease';
            
            // Salvar estado
            localStorage.setItem('statusPanelOpen', 'false');
        } else {
            // Abrir com animação
            statusContent.style.transition = 'max-height 0.4s ease-in-out, opacity 0.3s ease, padding 0.3s ease';
            statusContainer.classList.add('open');
            statusToggle.setAttribute('aria-expanded', 'true');
            
            // Animar ícone
            const icon = this.querySelector('.toggle-icon');
            icon.style.transition = 'transform 0.3s ease';
            
            // Salvar estado
            localStorage.setItem('statusPanelOpen', 'true');
        }
    });
    
    // Restaurar estado salvo (com animação suave)
    if (localStorage.getItem('statusPanelOpen') === 'true') {
        // Pequeno delay para carregar a página primeiro
        setTimeout(() => {
            statusContainer.classList.add('open');
            statusToggle.setAttribute('aria-expanded', 'true');
        }, 100);
    }
}

// Fechar ao clicar fora (opcional, mas mais suave)
document.addEventListener('click', function(event) {
    if (statusContainer && 
        statusContainer.classList.contains('open') && 
        !statusContainer.contains(event.target)) {
        
        statusContent.style.transition = 'max-height 0.3s ease-in-out, opacity 0.2s ease 0.1s, padding 0.3s ease';
        statusContainer.classList.remove('open');
        statusToggle.setAttribute('aria-expanded', 'false');
        
        const icon = statusToggle.querySelector('.toggle-icon');
        if (icon) {
            icon.style.transition = 'transform 0.3s ease';
            icon.style.transform = 'rotate(0deg)';
        }
        
        localStorage.setItem('statusPanelOpen', 'false');
    }
});

// ===== MENU MOBILE COM TRANSIÇÃO PERFEITA =====
function toggleMobileMenu() {
    const mobileContent = document.getElementById('mobileContent');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    
    if (!mobileContent.classList.contains('show')) {
        // ABRIR - primeiro mostra, depois anima
        mobileContent.classList.add('show');
        mobileContent.style.display = 'block';
        
        // Força um reflow antes de animar
        mobileContent.offsetHeight;
        
        // Anima para aberto
        mobileContent.style.maxHeight = mobileContent.scrollHeight + 'px';
        mobileContent.style.opacity = '1';
        mobileContent.style.padding = '20px';
        menuBtn.innerHTML = '✕';
    } else {
        // FECHAR - anima para fechado, DEPOIS esconde
        mobileContent.style.maxHeight = '0';
        mobileContent.style.opacity = '0';
        mobileContent.style.padding = '0 20px';
        menuBtn.innerHTML = '☰';
        
        // Depois da animação, remove a classe
        setTimeout(() => {
            if (mobileContent.style.maxHeight === '0px') {
                mobileContent.classList.remove('show');
                mobileContent.style.display = 'none';
            }
        }, 400); // Mesmo tempo da transição CSS
    }
}

// Fecha menu ao clicar em link
document.querySelectorAll('.menu a, .lang-link, .btn-logout').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            const mobileContent = document.getElementById('mobileContent');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            mobileContent.style.maxHeight = '0';
            mobileContent.style.opacity = '0';
            mobileContent.style.padding = '0 20px';
            menuBtn.innerHTML = '☰';
            
            setTimeout(() => {
                if (mobileContent.style.maxHeight === '0px') {
                    mobileContent.classList.remove('show');
                    mobileContent.style.display = 'none';
                }
            }, 400);
        }
    });
});

// Redimensionamento
window.addEventListener('resize', function() {
    const mobileContent = document.getElementById('mobileContent');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    
    if (window.innerWidth > 768) {
        // Desktop - sempre visível
        mobileContent.classList.add('show');
        mobileContent.style.display = 'block';
        mobileContent.style.maxHeight = 'none';
        mobileContent.style.opacity = '1';
        mobileContent.style.padding = '20px';
        menuBtn.style.display = 'none';
    } else {
        // Mobile - escondido por padrão
        menuBtn.style.display = 'block';
        menuBtn.innerHTML = '☰';
        mobileContent.classList.remove('show');
        mobileContent.style.display = 'none';
        mobileContent.style.maxHeight = '0';
        mobileContent.style.opacity = '0';
        mobileContent.style.padding = '0 20px';
    }
});

// Inicialização
window.addEventListener('load', function() {
    const mobileContent = document.getElementById('mobileContent');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    
    if (window.innerWidth <= 768) {
        menuBtn.style.display = 'block';
        menuBtn.innerHTML = '☰';
        mobileContent.classList.remove('show');
        mobileContent.style.display = 'none';
        mobileContent.style.maxHeight = '0';
        mobileContent.style.opacity = '0';
        mobileContent.style.padding = '0 20px';
    } else {
        menuBtn.style.display = 'none';
        mobileContent.classList.add('show');
        mobileContent.style.display = 'block';
        mobileContent.style.maxHeight = 'none';
        mobileContent.style.opacity = '1';
        mobileContent.style.padding = '20px';
    }
});