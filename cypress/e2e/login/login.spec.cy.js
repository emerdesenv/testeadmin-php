import Login from '../pages/login'

describe('Testes de Login', () => {
    beforeEach(() => {
        Login.visityPage();
    });
    it('Deve acusar alerta de usuário.', () => {
        cy.fixture('userData.json').then((userData) => {
            const usuarioIncorreto = userData.user.usuarioIncorreto;
            Login.login(usuarioIncorreto.username, usuarioIncorreto.password);
            cy.get('.alert > p').should("contain", "Usuário não encontrado.");
        });
    });
    it('Deve acusar alerta de autorização do usuário.', () => {
        cy.fixture('userData.json').then((userData) => {
            const usuarioSemAutorizacao = userData.user.usuarioSemAutorizacao;
            Login.login(usuarioSemAutorizacao.username, usuarioSemAutorizacao.password);
            cy.get('.alert > p').should("contain", "Não autorizado Usuário / Agenciador.");
        });
    });
    it('Deve acusar alerta de senha.', () => {
        cy.fixture('userData.json').then((userData) => {
            const senhaIncorreta = userData.user.senhaIncorreta;
            Login.login(senhaIncorreta.username, senhaIncorreta.password);
            cy.get('.alert > p').should("contain", "A senha inserida está incorreta.");
        });
    });
    it('Deve realizar o login com sucesso.', () => {
        cy.fixture('userData.json').then((userData) => {
            const usuarioSenhaCorreto = userData.user.usuarioSenhaCorreto;
            Login.login(usuarioSenhaCorreto.username, usuarioSenhaCorreto.password);
            cy.get('.navigation > :nth-child(1) > :nth-child(1) > a').should("contain", "Dashboard");
        });
    });
    it('Deve realizar o login e logout com sucesso.', () => {
        cy.fixture('userData.json').then((userData) => {
            const usuarioSenhaCorreto = userData.user.usuarioSenhaCorreto;
            Login.login(usuarioSenhaCorreto.username, usuarioSenhaCorreto.password);
            Login.logout();
        });
    });
});