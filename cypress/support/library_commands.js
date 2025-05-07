// Comandos para todas as telas
Cypress.Commands.add('select2', (containerSelector, optionText) => {
    cy.get(containerSelector).should('be.visible').click();
  
    cy.get('.select2-dropdown')
    .should('be.visible')
    .find('.select2-results__option')
    .contains(optionText)
    .should('be.visible')
    .click({ force: true });
});