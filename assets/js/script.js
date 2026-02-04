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

