<?php
    session_start();

    if(!isset($_SESSION["logged"])) {
        header("location: login");
    }

    /****************************************************************************
    * Funções de manipulação de configurações inicias
    ******************************************************************************/
    
    require_once("data.php");
    ini_set("max_execution_time", 7200);

    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION["TITULO"] = APP_TITLE;
    $_SESSION["NOMEDB"] = MYSQL_DATABASE;

    $data_db = new Data;
    $data_db->conectDataBase($_SESSION["NOMEDB"]);

    setcookie("lastfn", time(), strtotime("+1 year"), "/");
    
    /****************************************************************************
    * Funções gerais separadas por lógica e regra de negócio
    ******************************************************************************/

    require("pages_settings.php");
    require "extensive_value.php";
    require("date.php");
    require("format_value.php");
    require("generic_controller.php");
?>