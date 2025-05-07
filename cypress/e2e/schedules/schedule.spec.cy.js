import Schedule from '../pages/schedule'
describe('Teste de Agendamentos (Dashboard).', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        
        cy.fixture('userData.json').then((userData) => {
            const usuarioSenhaCorreto = userData.user.usuarioSenhaCorreto;
            cy.login_sessao(usuarioSenhaCorreto.username, usuarioSenhaCorreto.password);
        });
    });
    it('Deve deletar o registro que foi feito o cadastro.', () => {
        cy.visit(Cypress.env('url')+"/schedules");

        cy.fixture('clientData.json').then((clientData) => {
            const definitivo = clientData.client.definitivo;
           
            cy.get('.table-search-all > .input-group > .form-control').type(definitivo.cpf+'{enter}');

            // Aguarda a tabela ser populada com pelo menos uma linha visível
            cy.get('table tbody tr', { timeout: 10000 })
            .should('have.length.at.least', 1).and('be.visible')
            .then(($trs) => {
                const ids = [];

                $trs.each((_, tr) => {
                    const genericId = tr.getAttribute('generic_id');
                    if(genericId) ids.push(genericId);
                });

                // Agora, com todos os IDs, você pode deletar
                ids.forEach((id) => {
                    cy.log('Deletando id: ' + id);
                    cy.get("td").contains(id).parent().find(".more-options").should('be.not.disabled'); 
                    cy.click_dropdown_dt(id, "delete");

                    cy.wait(500) // espera meio segundo
                    
                    cy.get('.confirm-delete > .modal-dialog > .modal-content > .modal-footer > .delete-row').click({ force: true })
                    cy.get('#toast-container').should("contain", "Deletado com sucesso");
                });
            });
        });
    });
    it('Valida a consulta por cliente e seu histórico se esta vazio.', () => {
        cy.visit(Cypress.env('url')+"/index");
        cy.get('.controls > .btn-danger').click();

        cy.fixture('clientData.json').then((clientData) => {
            const definitivo = clientData.client.definitivo;

            cy.get('#client_search').type(definitivo.cpf+'{enter}');
            cy.get('.select2-results__option').contains(definitivo.id).click();

            cy.get('tbody > tr > .text-left').contains('Cliente não possui agendamentos.');
        });
    });
    it('Adiciona o agendamento com sucesso (Todos os campos).', () => {
        cy.visit(Cypress.env('url')+"/index");
        cy.get('.controls > .btn-danger').click();
        
        cy.fixture('clientData.json').then((clientData) => {
            const definitivo = clientData.client.definitivo;

            cy.get('#client_search').type(definitivo.cpf+'{enter}');
            cy.get('.select2-results__option').contains(definitivo.id).click();
        });

        cy.fixture('scheduleData.json').then((scheduleData) => {
            const completo = scheduleData.schedule.completo;
            Schedule.full(completo);
        });
    });
    it('Editando um agendamento, dando continuidade do anterior (Todos os campos).', () => {
        cy.visit(Cypress.env('url')+"/index");
        cy.get('.fc-list-event-title').click();
        
        cy.fixture('clientData.json').then((clientData) => {
            const definitivo = clientData.client.definitivo;
            cy.get('.mt-4 > :nth-child(1) > dd').contains(definitivo.id);
        });

        cy.fixture('scheduleData.json').then((scheduleData) => {
            const completo = scheduleData.schedule.completo;
            Schedule.full(completo, true);
        });

        cy.get('tr[style="border-color: green;"] > .fc-list-event-graphic > .fc-list-event-dot').should('be.visible');
    });
});