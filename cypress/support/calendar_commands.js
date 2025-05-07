// Aqui verifica a estrutura base de um datatable
Cypress.Commands.add("verify_calendar", ()=> {
    cy.get("#client-search").should("exist");
    cy.get("#calendar").should("exist");

    cy.get('.fc-dayGridMonth-button').click();
    cy.get('.fc-listDay-button').click();
    cy.get('.fc-list-empty').click();
});