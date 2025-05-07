class Login {
    
    visityPage() { 
        cy.visit(Cypress.env('url')); 
    }

    getAndfillUser(user) { cy.get('#user').type(user); }
    getAndfillPassword(password) { cy.get('#pass').type(password); }

    clickSelect() { cy.get('#select2-selector_agent-container').click(); }
    clickOption() { cy.get('.select2-results__option').contains('Casa dos Servidores').click(); }
    clickLogin() { cy.get('[type="submit"]').click(); }
    
    // Função que combina as ações para realizar o login
    login(username, password) {
        this.clickSelect();
        this.clickOption();

        this.getAndfillUser(username);
        this.getAndfillPassword(password);

        this.clickLogin();
    }

    logout() {
        cy.get('.navigation > :nth-child(1) > :nth-child(1) > a').should("contain", "Dashboard");
        cy.get('#userLogedinDropdown').click();
        cy.get('[href="logout"]').click();
        cy.get('.form-check-label').should("contain", "Lembrar-me");
    }
}

export default new Login()