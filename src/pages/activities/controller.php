<?php
    include "../../functions/main_config.php";
    include "service.php";

    $method = $_SERVER['REQUEST_METHOD'];
    
    $errors = [];

    $ActivitieService = new ActivitieService;

    switch($method) {
        case "POST":
            $code_activitie = $_POST["codigo"];

            if(!$data_db->checkDuplicateKey("atividade", "codigo", $_POST["codigo"])) {
                if(!$data_db->insertRegister("atividade", $_POST)) {
                    $errors[] = array("field" => "all", "message" => "Não foi possível gravar o registro!");
                }
            } else {
                $errors[] = [
                    "field"   => "codigo",
                    "message" => "Atividade '$code_activitie' já existe!"
                ];
            }
        break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_POST);
            
            if(!$data_db->updateRegister("atividade", $_POST, "id = ".$_POST["id"])) {
                $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro!"];
            }
        break;

        case "DELETE":
            $ActivitieService->deleteActivitiePermissions($_REQUEST["id"]);
            $data_db->deleteRegister($_REQUEST["id"], "atividade");
        break;

        default:
            $response = $ActivitieService->getAllActivities();
            $data_response = $response ? $response : [];
            
            echo json_encode(array("data" => $data_response));
            exit();
        break;
    }

    returnMessage($method, $errors);
?>