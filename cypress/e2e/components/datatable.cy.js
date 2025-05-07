beforeEach(() => {
    cy.viewport(1920, 1080);
    
    cy.fixture('userData.json').then((userData) => {
        const usuarioSenhaCorreto = userData.user.usuarioSenhaCorreto;
        cy.login_sessao(usuarioSenhaCorreto.username, usuarioSenhaCorreto.password);
    });
});

describe('Testes fluxo Datatable!', () => {
   
    const lista = [ "clients", "schedules" ];

    context('Lembar de dar as devidas permissões de acesso as páginas para rodar esse teste!', function() {
        lista.forEach(elem => {
            it(`Deve verificar se a datatable esa funcionando em ${elem}`, () => {
                cy.visit(Cypress.env('url')+"/"+elem);

                if(cy.url().should('be.equal', Cypress.env('url')+"/"+elem)) {
                    cy.verify_datatable(elem);
                }
            });
        });
    });
});