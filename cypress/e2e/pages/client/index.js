import { Api } from './api'

class Client extends Api {

    //Parte de chamadas obrigatórias
    getAndfillName(nome) { cy.get('#nome').type(nome); }
    getAndfillCPF(cpf) { cy.get('#cpf').type(cpf); }
    getAndfillPhone(celular) { cy.get('#celular').type(celular); }
    getAndfillCity(cidade) { cy.get('#cidade').type(cidade); }
   
    // Parte de chamadas opcionais
    getAndfillNumber(number) { cy.get('#numeroBeneficio').type(number); }
    getAndfillPhoneResidence(telefone) { cy.get('#telefone').type(telefone); }
    getAndfillDate(dataNascimento) { cy.get('#data_nascimento').type(dataNascimento); }
    getAndfillEmail(email) { cy.get('#email').type(email); }

    //Chamada de select ou ações de submissão
    clickSelect() { cy.select2('#select2-sexo-container', 'Prefiro não dizer'); }
    clickForm() { cy.get('#modal_form > .modal-footer > .btn-primary').click(); }
    clickCheck() { cy.get('#ativo').click(); }
    
    // Função que combina as ações para realizar o login
    mandatory(data, sendForm = true) {
        this.getAndfillName(data.nome);
        this.getAndfillCPF(data.cpf);
        this.getAndfillPhone(data.celular);
        this.getAndfillCity(data.cidade);
    
        this.clickSelect();
        
        if(sendForm) { this.clickForm(); }
    }

    full(data) {
        // Toda a parte de dados obrigatórios
        this.getAndfillName(data.nome);
        this.getAndfillCPF(data.cpf);
        this.getAndfillPhone(data.celular);

        this.clickSelect();
        this.getAndfillCity(data.cidade);
       
        // Toda a parte de dados opcional
        this.getAndfillNumber(data.numeroBeneficio);
        this.getAndfillPhoneResidence(data.telefone);
        this.getAndfillDate(data.dataNascimento);
        this.getAndfillEmail(data.email);

        this.clickForm();
    }

    edit() {
        cy.wait(500);
        this.clickForm();
    }

    delete(nameParam) {
        cy.get('.sorting_1', { timeout: 10000 })
        .should('contain', nameParam).first().invoke('text')
        .then((nome) => {
            cy.get('table tbody tr').should('have.length', 1).first().invoke('attr', 'generic_id')
            .then((id) => {
                if(nome.trim() === nameParam) {
                    this.unitDelete(id);
                }
            });
        });
    }
}

export default new Client()