<?php
    class ClientService {
        function getAllClients($params) {
            global $data_db;

            // Padrão responsavel por cuidar da performance da listagem e busca
            $draw            = $params["draw"];
            $row             = $params["start"];
            $rowperpage      = $params["length"];
            $columnIndex     = $params["order"][0]["column"];
            $columnName      = $params["columns"][$columnIndex]["data"] ? $params["columns"][$columnIndex]["data"] : "nome";
            $columnSortOrder = $params["order"][0]["dir"];
            $searchValue     = $params["search"]["value"];

            $search_query = " ";

            //Filtragem para armazenar lado front-end
            if($searchValue != "") {
                $new_search_value = trim($searchValue);

                $only_number = preg_replace("/[^0-9]/", "", $new_search_value);

                $cpf   = $only_number ? "OR c.CPF LIKE '%$only_number%'" : "";
                $phone = $only_number ? " OR REGEXP_REPLACE(c.celular, '\\\D', '') LIKE '%$only_number%'" : "";

                $where_sql_like = mb_strpos($new_search_value, "@") ? "" : $cpf.$phone;

                $search_query = " AND (
                    c.nome               LIKE '%$new_search_value%' 
                    OR c.numeroBeneficio LIKE '%$new_search_value%' 
                    $where_sql_like
                )";
            }

            $session_query = $this->filterSession();
            
            $where_id = isset($_SESSION["user_client"]["id"]) ? "AND c.idUsuario = ".$_SESSION["user_client"]["id"] : "";

            $sql_data_clients = $data_db->executeQuery(
                "SELECT CONCAT('row_', c.id) AS DT_RowId, c.id, c.idUsuario, c.idUsuarioExclusao, c.nome, c.numeroBeneficio , c.CPF, c.sexo,
                    c.dataNascimento, c.cidade, c.residenciaPropria, c.email, c.telefone,
                    c.celular, c.dataHoraCadastro, c.dataHoraExclusao, c.dataHoraAtualizacao, c.ativo, 
                    IF(c.ativo = 'S', 'Sim', 'Não') AS ativo
                FROM cliente c
                WHERE 1=1 $search_query $session_query $where_id
                ORDER BY $columnName $columnSortOrder
                LIMIT $row, $rowperpage"
            );

            $sel_total_clients = $data_db->executeGetLine(
                "SELECT COUNT(c.CPF) AS totalClientes 
                FROM cliente c 
                WHERE 1=1 $search_query $session_query $where_id"
            );
        
            $total_records = $sel_total_clients['totalClientes'];
            $data_response = [];

            $GenericService = new GenericService;

            $last_agent_id = 0;
           
            while($row_client = $data_db->getLine($sql_data_clients)) {

                $row_client["CPF"]            = maskGeneric("###.###.###-##", $row_client["CPF"]);
              
                $last_agent_id = $row_client["idUsuario"];
                $data_response[] = $row_client;
            }

            return [
                "draw"                  => $draw, 
                "totalRecordwithFilter" => $total_records, 
                "iTotalRecords"         => $data_db->linesAccount($sql_data_clients), 
                "data"                  => $data_response
            ];
        }

        function filterSession() {
            $status_client_active   = isset($_SESSION["status_client_active"]) ? $_SESSION["status_client_active"] : "";
            $status_client_inactive = isset($_SESSION["status_client_inactive"]) ? $_SESSION["status_client_inactive"] : "";

            $query_session = "";
    
            if($status_client_active || $status_client_inactive) {
                $session_query_status = "";
                $filter_status        = false;
    
                $session_query_status.= $status_client_active ? "OR c.ativo = '$status_client_active' " : "";
                $session_query_status.= $status_client_inactive ? "OR c.ativo = '$status_client_inactive' " : "";
                
                $filter_status = $session_query_status ? true : false;
                $query_status  = $session_query_status;
                
                $query_status_formated = " AND (".substr($query_status, 3).")";
    
                if($filter_status) {
                    $query_session = $query_status_formated;
                }
            }
    
            return $query_session;
        }

        function getClientByID($id_client) {
            global $data_db;

            return $data_db->executeGetLine("SELECT * FROM cliente WHERE id = $id_client");
        }
        
        function getUfByID($id_uf) {
            global $data_db;

            if($id_uf) {
                return $data_db->executeGetLine("SELECT * FROM uf WHERE id = $id_uf");
            }

            return false;
        }

        function getAllSchedulesByClientID($id_client) {
            global $data_db;
            
            $sql_data_schedules = $data_db->executeQuery(
                "SELECT a.id, a.idCliente, a.idPeriodo, a.idUsuario, a.obsPendente, a.obsAtendimento,
                        a.dataHoraAgendamento, a.dataHoraAtendimento, a.atendido, cl.nome, cl.CPF, p.descricao
                    FROM agendamento a
                INNER JOIN cliente cl ON cl.id = a.idCliente
                LEFT JOIN periodo p ON p.id = a.idPeriodo 
                WHERE a.idCliente = $id_client
                ORDER BY a.id DESC"
            );

            return $sql_data_schedules;
        }
    }
?>