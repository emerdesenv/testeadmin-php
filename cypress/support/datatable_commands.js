// Pegando o registro (aqui tratar somente para um registro com o nome evidenciado)
Cypress.Commands.add("click_dropdown_dt", (info, action)=> {
    cy.get("td").contains(info).parent().find(".more-options").should('be.not.disabled');

    cy.get("td").contains(info).parent().find(".more-options").click();
    cy.get("td").contains(info).parent().find("."+action).click();
});

// Aqui verifica a estrutura base de um datatable
Cypress.Commands.add("verify_datatable", (page)=> {
    cy.get(".bottom").should("exist");
    cy.get(".search-all").should("exist");

    cy.get("#"+page+"").should("exist");
    cy.get("#"+page+"_info").should("exist");

    cy.get('.search-all').should("exist");
});

// Aqui verifica a mensagem retornada após um processo de CRUD
Cypress.Commands.add("verify_message", (status)=> {
    let messages = {
        0 : "Adicionado com sucesso.",
        1 : "Alterado com sucesso.",
        2 : "Deletado com sucesso."
    };

    cy.log(messages[status]);

    cy.get('.toast').should('be.visible');
    cy.get('#toast-container').contains(messages[status].toString());
});

//Ações até deletar (Delet em Massa)

Cypress.Commands.add("delete_all", (tela)=> {
    cy.wait(500);

    cy.get("#"+tela+" tbody tr").then((elem) => {
        var minimo = 2;

        if(elem.length > 2) { minimo = elem.length; }

        cy.get("#"+tela+" tbody tr").should("have.length", minimo);
    });

    cy.get('thead > tr > .dt-checkboxes-cell > input').click();
    cy.get('.bulk-actions').select('Deletar');
    cy.get('#button_del').should("be.not.disabled");
    cy.get('#button_del').click();
    
    cy.get('#toast-container').should("contain", "Deletado com sucesso");
});