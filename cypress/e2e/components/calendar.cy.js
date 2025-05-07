beforeEach(() => {
    cy.viewport(1920, 1080);
    
    cy.fixture('userData.json').then((userData) => {
        const usuarioSenhaCorreto = userData.user.usuarioSenhaCorreto;
        cy.login_sessao(usuarioSenhaCorreto.username, usuarioSenhaCorreto.password);
    });
});

describe('Testes fluxo Calendário!', () => {
    context('Lembar de dar as devidas permissões de acesso as páginas para rodar esse teste!', function() {
        it('Deve verificar se o calendário esta funcional.', () => {
            cy.visit(Cypress.env('url')+"/index");

            if(cy.url().should('be.equal', Cypress.env('url')+"/index")) {
                cy.verify_calendar();
            }
        });
    })
});