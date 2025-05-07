<?php
    /****************************************************************************
    * Funções de manipulação de configurações inicias
    ******************************************************************************/

    $action   = isset($_GET["action"]) ? $_GET["action"] : false;
    $url_page = isset($_GET["url_page"]) ? $_GET["url_page"] : false;

    if(!$action or $action != "validate_login" and $action != "resend_code") {
        echo json_encode(["message" => "Sem dados para mostrar!", "error" => true]);
        exit();
    }

    /****************************************************************************
    * Funções de manipulação de configurações de inclusões
    ******************************************************************************/

    session_start();

    require_once("../functions/data.php");

    $_SESSION["TITULO"] = APP_TITLE;
    $_SESSION["NOMEDB"] = MYSQL_DATABASE;

    $data_db = new Data;
    $data_db->conectDataBase($_SESSION["NOMEDB"]);

    include("../functions/bcrypt.php");
    include("../functions/date.php");
    include("../functions/format_value.php");
    include("../functions/generic_controller.php");

    /****************************************************************************
    * Funções de manipulação de configurações de login
    ******************************************************************************/

    if($action == "validate_login") {
        $response = proccessLogin($_POST);
        
        echo json_encode(["detail" => $response]);
    }
?>