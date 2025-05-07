<?php
    include "../../functions/main_config.php";
    include "../../functions/filters_session.php";
    include "service.php";

    $method = $_SERVER['REQUEST_METHOD'];
    $info   = pageInfo(explode("/", $_SERVER["REQUEST_URI"])[2]);

    $errors = [];

    $ClientService = new ClientService;

    switch($method) {
        case "POST":
    
            date_format(new DateTime(), 'Y-m-d H:i:s');
            
            $_POST["CPF"] = isset($_POST["CPF"]) ? onlyNumber($_POST["CPF"]) : $_POST["CPF"];
            
            $skip_field_validation = ["ativo", "numeroBeneficio", "email", "sexo", "numero", "complemento", "telefone", "residenciaPropria", "dataNascimento"];

            // Validações realacionadas diretamente com preenchimento de cada campo

            foreach($_POST as $key => $value) {
                if(!in_array($key, $skip_field_validation)) {
                    if($value == "") {
                        $errors[] = ["field" => "all", "message" => "Preencha todos os campos obrigatórios!"]; break; 
                    }
                }
            }

            if(!$errors) {
                // Validações realacionadas diretamente com formatações dos campos

                if(strlen($_POST["CPF"]) != 11 or !validateCPF($_POST["CPF"])) {
                    $errors[] = ["field" => "CPF", "message" => "CPF inválido"];
                }

                if(isset($_POST["dataNascimento"]) and !empty($_POST["dataNascimento"]) and !validateBirthClient($_POST["dataNascimento"])) {
                    $errors[] = ["field" => "dataNascimento", "message" => "Data de Nascimento inválida!"];
                }
                
                $name_client = explode(" ", trim($_POST["nome"]));

                if(sizeof($name_client) < 2) {
                    $errors[] = ["field" => "nome", "message" => "Preencha o Nome e Sobrenome corretamente!"];
                }
                
                if(isset($_POST["email"]) and !empty($_POST["email"]) and $_POST["email"] and !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = ["field" => "email", "message" => "Preencha o Email corretamente!"];
                }

                if(!preg_match("/\([0-9]{2}\)\s[0-9]{5}\-[0-9]{4}/", $_POST["celular"])) {
                    $errors[] = ["field" => "celular", "message" => "Celular inválido!"];
                }

                // Validações realacionadas diretamente com o Banco de Dados

                if($data_db->checkDuplicateKey("cliente", array("CPF"), $_POST)) {
                    $errors[] = ["field" => "CPF", "message" => "Já existe um registro associado a esse CPF!"];
                } 
                
                if($data_db->checkDuplicateKey("cliente", array("celular"), $_POST)) {
                    $errors[] = ["field" => "celular", "message" => "Já existe um registro associado a esse Celular!"];
                } 
            }
            
            if(!$errors and !$data_db->insertRegister("cliente", $_POST)) {
                $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro!"];
            }
        break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_POST);
        
            if($_POST["status"] == "S") {
 
                date_format(new DateTime(), 'Y-m-d H:i:s');

                $_POST["CPF"]   = isset($_POST["CPF"]) ? onlyNumber($_POST["CPF"]) : $_POST["CPF"];
                $_POST["ativo"] = isset($_POST["ativo"]) ? "S": "N";
                $_POST["dataHoraAtualizacao"] = date_format(new DateTime(), 'Y-m-d H:i:s');

                $skip_field_validation = ["ativo", "numeroBeneficio", "email", "sexo", "numero", "complemento", "telefone", "residenciaPropria", "dataNascimento"];

                // Validações realacionadas diretamente com preenchimento de cada campo

                foreach($_POST as $key => $value) {
                    if(!in_array($key, $skip_field_validation)) {
                        if($value == "") {
                            $errors[] = ["field" => "all", "message" => "Preencha todos os campos obrigatórios!"]; break; 
                        }
                    }
                }

                if(!$errors) {
                    // Validações realacionadas diretamente com formatações dos campos

                    if(strlen($_POST["CPF"]) != 11 or !validateCPF($_POST["CPF"])) {
                        $errors[] = ["field" => "CPF", "message" => "CPF inválido"];
                    }

                    if(isset($_POST["dataNascimento"]) and !empty($_POST["dataNascimento"]) and !validateBirthClient($_POST["dataNascimento"])) {
                        $errors[] = ["field" => "dataNascimento", "message" => "Data de Nascimento inválida!"];
                    }
                    
                    $name_client = explode(" ", trim($_POST["nome"]));

                    if(sizeof($name_client) < 2) {
                        $errors[] = ["field" => "nome", "message" => "Preencha o Nome e Sobrenome corretamente!"];
                    }
                    
                    if(isset($_POST["email"]) and !empty($_POST["email"]) and $_POST["email"] and !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                        $errors[] = ["field" => "email", "message" => "Preencha o Email corretamente!"];
                    }

                    if(!preg_match("/\([0-9]{2}\)\s[0-9]{5}\-[0-9]{4}/", $_POST["celular"])) {
                        $errors[] = ["field" => "celular", "message" => "Celular inválido!"];
                    }

                    // Validações realacionadas diretamente com o Banco de Dados

                    if($data_db->checkDuplicateKey("cliente", array("CPF"), $_POST, $_POST["id"])) {
                        $errors[] = ["field" => "CPF", "message" => "Já existe um registro associado a esse CPF!"];
                    } 

                    if(isset($_POST["celular"]) and !empty($_POST["celular"]) and $_POST["celular"] and $data_db->checkDuplicateKey("cliente", array("celular"), $_POST, $_POST["id"])) {
                        $errors[] = ["field" => "celular", "message" => "Já existe um registro associado a esse Celular!"];
                    } 
                }
            } else {
                $data_client_db = $data_db->loadGenericObject($_POST["id"], "cliente");

                $_POST = $data_client_db;
                $_POST["ativo"] = "N";
            }
            
            if(!$errors and !$data_db->updateRegister("cliente", $_POST, "id = ".$_POST["id"])) {
                $errors[] = ["field" => "all", "message" => "Não foi possível salvar o registro!"];
            }
        break;

        case "DELETE":
            $data_db->deleteRegister($_REQUEST["id"], "cliente");
        break;

        default:
            $response = $ClientService->getAllClients($_GET);
            $data_response = $response ? $response : [];

            $data_response = array(
                "draw"                 => intval($data_response["draw"]),
                "iTotalRecords"        => $data_response["iTotalRecords"],
                "iTotalDisplayRecords" => $data_response["totalRecordwithFilter"],
                "aaData"               => $data_response["data"]
            );     
              
            echo json_encode($data_response);
            exit();
        break;
    }

    returnMessage($method, $errors);
?>