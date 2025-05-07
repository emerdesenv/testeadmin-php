<?php
    include "../../functions/main_config.php";
    include "service.php";

    $method  = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    $info    = pageInfo(explode("/", $_SERVER["REQUEST_URI"])[2]);
    
    $errors = [];
    
    $TextService = new TextService;

    switch($method) {
        case "POST":
            $text_code = $_POST["codigo"];

            if(!$data_db->checkDuplicateKey("texto", array("codigo"), $_POST)) {
                if(!$data_db->insertRegister("texto", $_POST)) {
                    $errors[] = array("field" => "all", "message" => "Não foi possível gravar o registro!");
                }
            } else {
                $errors[] = [
                    "field"   => "codigo",
                    "message" => "Já existe um registro para '$text_code'!"
                ];
            }
        break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_POST);

            $text_code = $_POST["codigo"];

            if(!$data_db->checkDuplicateKey("texto", array("codigo"), $_POST, $_POST["id"])) {
                if(!$data_db->updateRegister("texto", $_POST, "id = ".$_POST["id"])) {
                    $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro!"];
                }
            } else {
                $errors[] = [
                    "field"   => "codigo",
                    "message" => "O código '$text_code' já existe!"
                ];
            }
        break;

        case "DELETE":
            $data_db->deleteRegister($_REQUEST["id"], "texto");
        break;

        default:
            $response = $TextService->getAllTexts();
            $data_response = $response ? $response : [];
            
            echo json_encode(array("data" => $data_response));
            exit();
        break;
    }

    returnMessage($method, $errors);
?>