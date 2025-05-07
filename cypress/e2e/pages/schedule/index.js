class Schedule {
    // Parte de chamadas opcionais
    getAndfillTitle(titulo) { cy.get('#obs_pendente').type(titulo); }
    getAndfillDate(dataHora) { cy.get('#data_hora_agendamento').type(dataHora); }
    getAndfillDescription(descricao) { cy.get('#obs_atendimento').type(descricao); }

    //Chamada de select ou ações de submissão
    clickForm() { cy.get('#event_modal > .modal-dialog > .modal-content > .modal-footer > .btn-primary').click(); }
  
    // Função que combina as ações para realizar o login
    full(data, clearField = true) {
        const formatted = this.getCurrentFormattedDate();
        
        this.getAndfillTitle(data.titulo);
        this.getAndfillDate(formatted);
        this.getAndfillDescription(data.observacoes);
        
        if(clearField) { 
            cy.get('#obs_pendente').clear(); 
            this.getAndfillTitle("Continuação agendamento Objetiva Teste");
        }
        
        this.clickForm();
    }

    getCurrentFormattedDate() {
        const now = new Date()
      
        const year = now.getFullYear()
        const month = String(now.getMonth() + 1).padStart(2, '0') // mês começa em 0
        const day = String(now.getDate()).padStart(2, '0')
        const hours = String(now.getHours()).padStart(2, '0')
        const minutes = String(now.getMinutes()).padStart(2, '0')
      
        return `${year}-${month}-${day}T${hours}:${minutes}`
    }
}

export default new Schedule()