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
