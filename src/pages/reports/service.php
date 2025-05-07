<?php
    class ReportService {
        function reportSchedule() {
            global $data_db;

            function convertDate($date) {
                if(strstr($date, "-") || strstr($date, "/")) {
                        $date = preg_split("/[\/]|[-]+/", $date);
                        $date = $date[2]."-".$date[1]."-".$date[0];
                        return $date;
                };

                return false;
            };

            function getPeriods($interval) {
                list($start_date, $end_date) = explode(' - ', $interval);
                $start_date = convertDate($start_date);
                $end_date = convertDate($end_date);
                
                return [
                    'start_date' => $start_date,
                    'end_date'   => $end_date
                ];
            };

            $session_query = $this->filterSession();
            $interval = isset($_SESSION["schedule_period"]) ? getPeriods($_SESSION["schedule_period"]) : ""; 

            $where_sql    = $_SESSION["user"]["tipoUsuario"] == 0 ? "" : "AND a.idUsuario = ".$_SESSION["user"]["id"];
            $where_id     = isset($_SESSION["user_client"]["id"]) ? "AND a.idUsuario = ".$_SESSION["user_client"]["id"] : ""; 
            $where_period = isset($_SESSION["schedule_period"]) ? "AND a.dataHoraAgendamento BETWEEN '{$interval['start_date']}' AND '{$interval['end_date']}'" : "AND DATE(a.dataHoraAgendamento) = CURDATE()";


            $sql_data_schedules = $data_db->executeQuery(
                "SELECT CONCAT('row_', a.id) AS DT_RowId, a.id, a.obsPendente , a.obsAtendimento,
                    a.dataHoraAgendamento, a.dataHoraAtendimento, a.atendido, c.nome, c.CPF, c.dataNascimento, p.descricao,
                    u.codigo AS codigoUsuario, u.nome AS nomeUsuario, u.id AS idUsuario,
                    IF(c.ativo = 'S', 'Sim', 'Não') AS ativo
                FROM agendamento a
                INNER JOIN usuario u ON u.id = a.idUsuario
                INNER JOIN cliente c ON c.id = a.idCliente
                LEFT JOIN periodo p ON p.id = a.idPeriodo
                WHERE 1=1 $where_sql $session_query $where_id $where_period
                ORDER BY u.codigo ASC, a.id DESC 
                LIMIT 500"
            );

            return $sql_data_schedules;
        }

        function filterSession() {
            $status_schedule_served   = isset($_SESSION["status_schedule_served"]) ? $_SESSION["status_schedule_served"] : "";
            $status_schedule_pending = isset($_SESSION["status_schedule_pending"]) ? $_SESSION["status_schedule_pending"] : "";

            $query_session = "";
    
            if($status_schedule_served || $status_schedule_pending) {
                $session_query_status = "";
                $filter_status        = false;
    
                $session_query_status.= $status_schedule_served ? "OR a.atendido = '$status_schedule_served' " : "";
                $session_query_status.= $status_schedule_pending ? "OR a.atendido = '$status_schedule_pending' " : "";
                
                $filter_status = $session_query_status ? true : false;
                $query_status  = $session_query_status;
                
                $query_status_formated = " AND (".substr($query_status, 3).")";
    
                if($filter_status) {
                    $query_session = $query_status_formated;
                }
            }
    
            return $query_session;
        }
    }
?>