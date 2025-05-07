import Client from '../pages/client/index'
describe('Teste de Cliente.', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        
        cy.fixture('userData.json').then((userData) => {
            const usuarioSenhaCorreto = userData.user.usuarioSenhaCorreto;
            cy.login_sessao(usuarioSenhaCorreto.username, usuarioSenhaCorreto.password);
        });

        cy.visit(Cypress.env('url')+"/clients");
    });
    it('Deve deletar o registro que foi feito o cadastro (2 registros).', () => {
        cy.fixture('clientData.json').then((clientData) => {
            const camposOrigatorio = clientData.client.camposOrigatorio;
            const completo = clientData.client.completo;
            
            cy.get('.table-search-all > .input-group > .form-control').type(camposOrigatorio.cpf+'{enter}');
            cy.get('table tbody tr').should('have.length.at.least', 1);
            
            Client.delete(camposOrigatorio.nome);

            cy.get('.col-md-3 > .input-group > .form-control').clear();
            
            cy.get('.col-md-3 > .input-group > .form-control').type(completo.cpf+'{enter}');
            cy.get('table tbody tr').should('have.length.at.least', 1);

            Client.delete(completo.nome);
        });
    });
    it('Deve acusar alera de CPF já cadastrado.', () => {
        cy.get('.controls > .btn-danger').click();

        cy.fixture('clientData.json').then((clientData) => {
            const definitivo = clientData.client.definitivo;
            Client.mandatory(definitivo, false);
            cy.get('.invalid-feedback').should("contain", "CPF já registrado!");
        });
    });
    it('Deve acusar alera de Celular já cadastrado.', () => {
        cy.get('.controls > .btn-danger').click();

        cy.fixture('clientData.json').then((clientData) => {
            const celuarExistente = clientData.client.celularExistente;
            Client.mandatory(celuarExistente);
            cy.get('.invalid-feedback').should("contain", "Já existe um registro associado a esse Celular!");
        });
    });
    it('Deve realizar o cadastro com sucesso (Somente dados obrigatórios).', () => {
        cy.get('.controls > .btn-danger').click();

        cy.fixture('clientData.json').then((clientData) => {
            const camposOrigatorio = clientData.client.camposOrigatorio;
            Client.mandatory(camposOrigatorio);
            cy.verify_message(0);
        });
    });
    it('Deve realizar o cadastro com sucesso (Todos os dados).', () => {
        cy.get('.controls > .btn-danger').click();

        cy.fixture('clientData.json').then((clientData) => {
            const completo = clientData.client.completo;
            Client.full(completo);
            cy.verify_message(0);
        });
    });
    it('Deve realizar a atualização de um cadastro (Sem alterações de dados).', () => {
        cy.fixture('clientData.json').then((clientData) => {
            const definitivo = clientData.client.definitivo;
            cy.get('.col-md-3 > .input-group > .form-control').type(definitivo.cpf+'{enter}');

            cy.click_dropdown_dt(definitivo.id, "edit");
            Client.edit();
            cy.verify_message(1);
        });
    });
    it('Busca os clientes cadastrados.', () => {
        cy.fixture('clientData.json').then((clientData) => {
            const camposOrigatorio = clientData.client.camposOrigatorio;
            const completo = clientData.client.completo;

            cy.get('.col-md-3 > .input-group > .form-control').type(camposOrigatorio.cpf+'{enter}');
            cy.get("td").contains(camposOrigatorio.nome).should('be.visible');

            cy.get('.col-md-3 > .input-group > .form-control').clear();

            cy.get('.col-md-3 > .input-group > .form-control').type(completo.cpf+'{enter}');
            cy.get("td").contains(completo.nome).should('be.visible');
        });
    });
});