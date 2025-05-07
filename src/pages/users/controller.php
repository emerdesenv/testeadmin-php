<?php
    include "../../functions/main_config.php";
    include "service.php";

    $method = $_SERVER['REQUEST_METHOD'];
    
    $errors = [];

    $UserService = new UserService;

    if(isset($_GET["from"]) and $_GET["from"] == "mydata") {
        $method = "PUT";
    }

    switch($method) {
        case "POST":
            $code_user = $_POST["codigo"];

            if(!$data_db->checkDuplicateKey("usuario", "codigo", $_POST["codigo"])) {

                require "../../functions/bcrypt.php";

                $db = $data_db->connection();
                $password = $db->real_escape_string($_POST["senha"]);
                $_POST["salt"] = Bcrypt::hash($password);

                $_POST["ativo"]  = isset($_POST["ativo"]) ? "S" : "N";
                $_POST["modelo"] = isset($_POST["modelo"]) ? "S" : "N";

                if(!$data_db->insertRegister("usuario", $_POST)) {
                    $errors[] = array("field" => "all", "message" => "Não foi possível gravar o registro!");
                }
            } else {
                $errors[] = [
                    "field"   => "codigo",
                    "message" => "Registro '$code_user' já existe!"
                ];
            }
        break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_POST);

            if(isset($_FILES["avatar"])) {
                proccessImageUser($_FILES["avatar"]);
                break;
            }

            if(isset($_GET["from"]) and !($_POST["id"] == $_SESSION["user"]["id"])) {
                die("Falha ao editar seus dados");
            }

            $id = $_POST["id"]; unset($_POST["id"]);

            if($_POST["senha"] != "" and $_POST["senha2"] != "") {
                require "../../functions/bcrypt.php";
                
                $db = $data_db->connection();
                $password = $db->real_escape_string($_POST["senha"]);
                $_POST["salt"] = Bcrypt::hash($password);
            }

            $_POST["ativo"]  = isset($_POST["ativo"]) ? "S" : "N";
            $_POST["modelo"] = isset($_POST["modelo"]) ? "S" : "N";
           
            if(!$data_db->updateRegister("usuario", $_POST, "id = $id")) {
                $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro!"];
            }
        break;

        case "DELETE":
            $UserService->deletePermissionsUser($_REQUEST["id"]);
            
            $data_db->deleteRegister($_REQUEST["id"], "usuario");
        break;

        default:
            $response = $UserService->getAllUsers();
            $data_response = [];

            if(count($response) > 0) {
                foreach($response as $row) {
                    $row["dataNascimento"] = dateCompleteBR($row["dataNascimento"]);
                    $data_response[] = $row;
                }
            }

            echo json_encode(array("data" => $data_response));
            exit();
        break;
    }

    if(isset($_GET["from"]) and $_GET["from"] == "mydata" and !isset($_FILES["avatar"])) {
        header("Location: /mydata?msg=true");
    }

    returnMessage($method, $errors);
?>