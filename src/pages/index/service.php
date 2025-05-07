<?php
    class SchedulingService {
        function getAllScheduling($start, $end, $status, $client) {
            global $data_db;

            $status = $status == "all" ? "IN('S', 'N')" : "IN('$status')";
            $search_query = "";

            $new_date_init  = explode("T", $start)[0];
            $new_date_final = explode("T", $end)[0];

            if($client != "false") {
                $only_number = preg_replace("/[^0-9]/", "", $client);

                $cpf   = $only_number ? "OR c.CPF LIKE '%$only_number%'" : "";
                $phone = $only_number ? " OR REGEXP_REPLACE(c.celular, '\\\D', '') LIKE '%$only_number%'" : "";
    
                $where_sql_like = $cpf.$phone;
    
                $search_query = " AND (
                    c.nome LIKE '%$client%' 
                    $where_sql_like
                )";
            }
            
            $sql_data_scheduling = $data_db->executeQuery(
            "SELECT a.*, c.nome, c.id AS idCliente
            FROM agendamento a
            INNER JOIN cliente c ON c.id = a.idCliente 
            WHERE dataHoraAgendamento BETWEEN '$new_date_init' AND '$new_date_final' AND atendido $status $search_query AND a.idUsuario = ".$_SESSION["user"]["id"]);

            $events = [];

            if($sql_data_scheduling->num_rows > 0) {
                while($row = $data_db->getLine($sql_data_scheduling)) {
                    $events[] = [
                        "id"              => $row["id"],
                        "idCliente"       => $row["idCliente"],
                        "title"           => $row["nome"],
                        "obsPendente"     => $row["obsPendente"],
                        "start"           => $row["dataHoraAgendamento"],
                        "obsAtendimento"  => $row["obsAtendimento"],
                        "completed"       => $row["atendido"] == "N" ? false : true,
                        "backgroundColor" => $row["atendido"]  == "S" ? "green" : "#ff7c7c",
                        "borderColor"     => $row["atendido"]  == "S" ? "green" : "#ff7c7c"
                    ];
                }
            }
    
            return $events;
        }

        function getAllClientsByKey($key_search) {
            global $data_db;

            $only_number = preg_replace("/[^0-9]/", "", $key_search);

            $cpf   = $only_number ? "OR CPF LIKE '%$only_number%'" : "";
            $phone = $only_number ? " OR REGEXP_REPLACE(celular, '\\\D', '') LIKE '%$only_number%'" : "";

            $where_sql_like = $cpf.$phone;

            $search_query = " AND (
                nome LIKE '%$key_search%' 
                $where_sql_like
            )";

            $sql_data_clients = $data_db->executeQuery("SELECT * FROM cliente WHERE ativo = 'S' $search_query");

            return $data_db->getAllLines($sql_data_clients);
        }

        function getSchedulingByID($id_scheduling) {
            global $data_db;

            return $data_db->executeGetLine("SELECT * FROM agendamento WHERE id = $id_scheduling");
        }

        function getClientByID($id_client) {
            global $data_db;

            return $data_db->executeGetLine("SELECT nome FROM cliente WHERE id = $id_client");
        }

        function getPeriod($code) {
            global $data_db;

            return $data_db->executeGetLine("SELECT * FROM periodo WHERE codigo = $code");
        }

        function updateStatus($id, $status) {
            global $data_db;

            $date_confirm = $status == "S" ? "dataHoraAtendimento = '".date("Y-m-d H:i:s")."'" : "dataHoraAtendimento = NULL";
           
            $data_db->executeQuery("UPDATE agendamento SET atendido = '$status', $date_confirm WHERE id = $id");
        }

        function updateLastScheduleToAttendedByClientID($user_id, $client_id, $event_data, $event_id) {
            global $data_db;
            $sql_data = "";
           
            $last_schedule = $data_db->executeGetLine(
                "SELECT id FROM agendamento WHERE idCliente = '$client_id' AND id != '$event_id' AND idUsuario = '$user_id' ORDER BY id DESC LIMIT 1"
            );

            if($last_schedule) {
                $sql_data = $data_db->executeQuery(
                    "UPDATE agendamento SET atendido = 'S', dataHoraAtendimento = '$event_data' WHERE id = {$last_schedule['id']}"
                );
            }
        
            return [
                "last_schedule" => $last_schedule ? $last_schedule["id"] : "Não há agendamentos anteriores.",
                "success"       => $last_schedule ? $sql_data : false
            ];
        }
    }
?>