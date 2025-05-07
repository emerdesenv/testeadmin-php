class Api {
    unitDelete(id) {
        cy.api({
            method: "DELETE",
            url: Cypress.env('url')+"/pages/clients/controller?id="+id,
            failOnStatusCode: false
        }).then(
            (response) => {
                cy.log(response);
                expect(response.body).to.eq("Deletado com sucesso.");
            }
        );
    }
}

module.exports = { Api };