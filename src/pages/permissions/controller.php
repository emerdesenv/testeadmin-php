<?php
    include "../../functions/main_config.php";
    include "service.php";

    $method = $_SERVER['REQUEST_METHOD'];
    
    $errors = [];

    $from = isset($_GET["from"]) ? $_GET["from"] : false;
    
    $PermissionService = new PermissionService;

    switch($method) {
        case "POST":
            if($from == "modelo") {
                if(!$PermissionService->copyPermissionsByModel($_POST["idAgenciador"], $_POST["modelo"], $_POST["idUsuario"])) {
                    $errors[] = array("all" => "Não foi possível gravar o registro");
                }
            } else {
                $_POST["inclusao"]  = isset($_POST["inclusao"])  ? "S" : "N";
                $_POST["alteracao"] = isset($_POST["alteracao"]) ? "S" : "N";
                $_POST["exclusao"]  = isset($_POST["exclusao"])  ? "S" : "N";
                
                if(!$data_db->checkDuplicateKey("permissao", array("idAgenciador", "idUsuario", "idAtividade") , $_POST)) {
                    if(!$data_db->insertRegister("permissao", $_POST)) {
                        $errors[] = array("field" => "all", "message" => "Não foi possível gravar o registro");
                    }
                } else {
                    $errors[] = ["field" => "idAtividade", "message" => "Atividade já existe para este usuário neste agenciador"];  
                }
            }
        break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_POST);

            $_POST["inclusao"]  = isset($_POST["inclusao"])  ? "S" : "N";
            $_POST["alteracao"] = isset($_POST["alteracao"]) ? "S" : "N";
            $_POST["exclusao"]  = isset($_POST["exclusao"])  ? "S" : "N";

            if(!$data_db->updateRegister("permissao", $_POST, "id = ".$_POST["id"])) {
                $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro"];
            }
        break;
        
        case "DELETE":
            $data_db->deleteRegister($_REQUEST["id"], "permissao");
        break;

        default:
            $response = $PermissionService->getAllPermissions();
            $data = $response ? $response : [];

            echo json_encode(array("data" => $data));
            exit();
        break;
    }

    returnMessage($method, $errors);
?>