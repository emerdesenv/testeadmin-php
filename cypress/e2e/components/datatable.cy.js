beforeEach(() => {
    cy.viewport(1920, 1080);
    
    cy.fixture('userData.json').then((userData) => {
        const usuarioSenhaCorreto = userData.user.usuarioSenhaCorreto;
        cy.login_sessao(usuarioSenhaCorreto.username, usuarioSenhaCorreto.password);
    });
});