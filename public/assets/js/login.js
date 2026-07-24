// login.js

// toggle de visibilidade da senha
const btnVerSenha = document.getElementById('btn-ver-senha');
const inputSenha  = document.getElementById('senha');

if (btnVerSenha && inputSenha) {
    btnVerSenha.addEventListener('click', function() {
        const visivel = inputSenha.type === 'text';
        inputSenha.type = visivel ? 'password' : 'text';

        this.querySelector('.olho-aberto').classList.toggle('escondido', !visivel);
        this.querySelector('.olho-fechado').classList.toggle('escondido', visivel);
    });
}

// estado de carregamento no submit
const formLogin = document.getElementById('form-login');
const btnEntrar = document.getElementById('btn-entrar');

if (formLogin && btnEntrar) {
    formLogin.addEventListener('submit', function() {
        btnEntrar.disabled = true;
        btnEntrar.querySelector('.texto-btn').classList.add('escondido');
        btnEntrar.querySelector('.carregando').classList.remove('escondido');
    });
}
