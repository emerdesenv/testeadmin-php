Cypress.Commands.add("login_sessao", (user, pass)=> {
    cy.session([user, pass], () => {
            cy.visit(Cypress.env('url'));

            cy.get('#select2-selector_agent-container').click();
            cy.get('.select2-results__option').contains('Casa dos Servidores').click();
            cy.get('#user').type(user);
            cy.get('#pass').type(pass);
        
            cy.get('[type="submit"]').click();
        }, {
            cacheAcrossSpecs: true
        }
    );
});