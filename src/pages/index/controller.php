<?php
    include "../../functions/main_config.php";
    include "service.php";

    $method  = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    
    $errors = [];

    $SchedulingService = new SchedulingService;

    switch($method) {
        case "POST":
            $_POST["idUsuario"]   = $_SESSION["user"]["id"];
            $update_last_schedule = isset($_POST["update_last_schedule"]) ? true : false;
            $response      = [];
            $return_update = false;
            
            $hora = substr($_POST["dataHoraAgendamento"], 11, 5);
            
            $code_period = !empty($hora) ? getPeriod($hora) : "3";
            $code_period = $SchedulingService->getPeriod($code_period);

            $_POST["idPeriodo"] = $code_period["id"];

            $return_insert = $data_db->insertRegister("agendamento", $_POST, true);

            if(!$return_insert) {
                $errors[] = array("field" => "all", "message" => "Não foi possível gravar o registro!");
            } else {
                $return_client = $SchedulingService->getClientByID($_POST["idCliente"]);
                
                if($update_last_schedule == true) {
                    $return_update = $SchedulingService->updateLastScheduleToAttendedByClientID($_POST["idUsuario"], $_POST["idCliente"], $_POST["dataHoraAtendimento"], $return_insert);
                };

                $response = [
                    "success" => true, 
                    "id"      => $return_insert, 
                    "nome"    => $return_client["nome"],
                    "update"  => $return_update
                ];

                echo json_encode($response);
            }
        break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_POST);

            $obs_pending_new        = isset($_POST["newSchedule"]) ? $_POST["obsPendente"]    : NULL;
            $obs_attended_new       = isset($_POST["newSchedule"]) ? $_POST["obsAtendimento"] : NULL;
            $date_hour_schedule_new = isset($_POST["newSchedule"]) ? str_replace('T', ' ', $_POST["dataHoraAgendamento"]) : NULL;

            //Agendamento Edição
            $_POST["atendido"]            = $_POST["atendido"] == "true" ? "S" : "N";
            $_POST["obsPendente"]         = $_POST["obsPendenteEdicao"];
            $_POST["obsAtendimento"]      = $_POST["obsAtendimentoEdicao"] ? $_POST["obsAtendimentoEdicao"] : NULL;
            $_POST["dataHoraAtendimento"] = $_POST["atendido"] == "S" ? date("Y-m-d H:i:s") : NULL;
            $_POST["dataHoraAgendamento"] = str_replace('T', ' ', $_POST["dataHoraAgendamentoEdicao"]);

            $hour_edition = substr($_POST["dataHoraAgendamentoEdicao"], 11, 5);

            $code_period = !empty($hour_edition) ? getPeriod($hour_edition) : "3";
            $code_period = $SchedulingService->getPeriod($code_period);

            $_POST["idPeriodo"] = $code_period["id"];
            
            if(!$data_db->updateRegister("agendamento", $_POST, "id = ".$_POST["id"])) {
                $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro!"];
            } else if(!isset($_POST["newSchedule"])) {
                $return_client = $SchedulingService->getClientByID($_POST["idCliente"]);
                echo json_encode(array("success" => true, "id" => $_POST["id"], "nome" => $return_client["nome"]));
            }

            //Novo Agendamento
            if(isset($_POST["newSchedule"])) {
                $data_scheduling_db           = $data_db->loadGenericObject($_POST["id"], "agendamento");

                $_POST["atendido"]            = "N";
                $_POST["dataHoraAtendimento"] = date("Y-m-d H:i:s");
                $_POST["idUsuario"]           = $_SESSION["user"]["id"];

                $_POST["obsPendente"]         = $obs_pending_new;
                $_POST["obsAtendimento"]      = $obs_attended_new;
                $_POST["dataHoraAgendamento"] = $date_hour_schedule_new;

                $hora        = substr($_POST["dataHoraAgendamento"], 11, 5);
                $code_period = !empty($hora) ? getPeriod($hora) : "3";
                $code_period = $SchedulingService->getPeriod($code_period);
                
                $_POST["idPeriodo"] = $code_period["id"];
                $_POST["idCliente"] = $data_scheduling_db["idCliente"];
                             
                $return = $data_db->insertRegister("agendamento", $_POST, true);

                if(!$return) {
                    $errors[] = array("field" => "all", "message" => "Não foi possível gravar o registro!");
                } else {
                    $return_client = $SchedulingService->getClientByID($_POST["idCliente"]);
                    echo json_encode(array("success" => true, "id" => $return, "nome" => $return_client["nome"]));
                }
            }
        break;

        case "DELETE":
            $data_db->deleteRegister($_REQUEST["id"], "agendamento");
        break;

        default:
            $status = isset($_GET["status"]) ? $_GET["status"] : "all";
            $start  = $_GET["start"];
            $end    = $_GET["end"];
            $client = $_GET["client"];

            $response = $SchedulingService->getAllScheduling($start, $end, $status, $client);
            $data_response = $response ? $response : [];
            
            echo json_encode($data_response);
            exit();
        break;
    }
?>