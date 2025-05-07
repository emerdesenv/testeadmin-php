<?php
    include "../../functions/main_config.php";
    include "service.php";

    $method  = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    $info    = pageInfo(explode("/", $_SERVER["REQUEST_URI"])[2]);

    $errors = [];

    $id_activitie = $info["idAtividade"];

    $AgentService = new AgentService;

    switch($method) {
        case "POST":
            if(!$data_db->checkDuplicateKey("agenciador", array("CNPJ_CPF"), $_POST)) {
                if($data_db->insertRegister("agenciador", $_POST)) {

                    $AgentService->insertAgentPermission();

                    unset($_SESSION["Pode_acessar"]);
                } else {
                    $errors[] = array("field" => "all", "message" => "Não foi possível gravar o registro!");
                }
            } else {
                $errors[] = [
                    "field"   => "CNPJ_CPF",
                    "message" => "Agenciador já existe!"
                ];
            }
        break;

        case "DELETE":
            $data_db->deleteRegister($_REQUEST["id"], "agenciador");
        break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_POST);

            $_POST["ativo"] = isset($_POST["ativo"]) ? "S": "N";
            
            if(!$data_db->updateRegister("agenciador", $_POST, "id = ".$_POST["id"])) {
                $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro!"];
            }
        break;

        default:
            $response = $AgentService->getAllAgents($id_activitie);
            $data_response = $response ? $response : [];
            
            echo json_encode(array("data" => $data_response));
            exit();
        break;
    }

    returnMessage($method, $errors);
?>