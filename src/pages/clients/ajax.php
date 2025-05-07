<?php
    include "../../functions/main_config.php";
    include "service.php";

    $ClientService = new ClientService;

    $action = $_GET["action"];
    $id_client = isset($_GET["id_client"]) ? $_GET["id_client"] : false;

    if($action == "check_duplicate_cpf") { 
        if ($id_client){
            if ($data_db->checkDuplicateKey("cliente", "CPF", $_GET["cpf"], $id_client)) {
                $array_return = true;
            } else {
                $array_return = false;
            }
        } else if ($data_db->checkDuplicateKey("cliente", "CPF", $_GET["cpf"])) {
            $array_return = true;
        } else {
            $array_return = false;
        }

        echo json_encode($array_return);
    }
?>