// dashboard.js

const sidebar  = document.getElementById('sidebar');
const btnMenu  = document.getElementById('btn-menu');
const overlay  = document.getElementById('overlay');

// abre e fecha a sidebar no mobile
if (btnMenu) {
    btnMenu.addEventListener('click', function() {
        const aberta = sidebar.classList.contains('aberta');
        aberta ? fecharMenu() : abrirMenu();
    });
}

if (overlay) {
    overlay.addEventListener('click', fecharMenu);
}

// fecha com ESC também
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') fecharMenu();
});

function abrirMenu() {
    sidebar.classList.add('aberta');
    overlay.classList.add('visivel');
}

function fecharMenu() {
    sidebar.classList.remove('aberta');
    overlay.classList.remove('visivel');
}

// ajusta a saudação de acordo com o horário
const saudacaoEl = document.getElementById('texto-saudacao');
if (saudacaoEl) {
    const hora     = new Date().getHours();
    const nome     = saudacaoEl.textContent.split(',')[1]?.trim() ?? 'Usuário';
    let saudacao   = 'Olá';

    if (hora >= 5 && hora < 12)       saudacao = 'Bom dia';
    else if (hora >= 12 && hora < 18) saudacao = 'Boa tarde';
    else                               saudacao = 'Boa noite';

    saudacaoEl.textContent = saudacao + ', ' + nome + '!';
}
