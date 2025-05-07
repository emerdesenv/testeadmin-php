<?php
    class AgentService {
        function getAllAgents($id_activitie) {
            global $data_db;

            $id_user = $_SESSION["user"]["id"];
            
            $sql_data_agents = $data_db->executeQuery(
            "SELECT CONCAT('row_', ag.id) AS DT_RowId, ag.id, ag.idUf, ag.tipo, ag.CNPJ_CPF, ag.nome, ag.CEP, ag.endereco,
                ag.numero, ag.complemento, ag.bairro, ag.cidade, ag.telefone, ag.celular, ag.email, ag.dataHoraCadastro, ag.ativo,
                IF(ag.ativo = 'S', 'Sim', 'Não') AS ativo
            FROM agenciador ag");
    
            return $data_db->getAllLines($sql_data_agents);
        }
    
        function insertAgentPermission() {
            global $data_db;

            $id_user = $_SESSION["user"]["id"];
    
            $sql_data_agents = $data_db->executeGetLine("SELECT MAX(id) AS id_agenciador FROM agenciador");
            $data_activitie  = $data_db->executeGetLine("SELECT id FROM atividade WHERE codigo = 'CRUD_PERMISSIONS'");
            
            $data_db->insertRegister("permissao", [
                "idAgenciador"=> $sql_data_agents["id_agenciador"],
                "idUsuario"   => $id_user,
                "idAtividade" => $data_activitie["id"],
                "inclusao"    => "S",
                "alteracao"   => "S",
                "exclusao"    => "S"
            ]);
        }

        function getAgentByID($id_agent) {
            global $data_db;
            
            $sql_data_agent = $data_db->executeGetLine("SELECT * FROM agenciador WHERE id = $id_agent");

            return $sql_data_agent;
        }

        function getAllIPsByAgentID($id_agent) {
            global $data_db;

            $sql_data_ips = $data_db->executeQuery("SELECT * FROM ips WHERE idAgenciador = $id_agent"); 

            return $sql_data_ips;
        }
    }
?>