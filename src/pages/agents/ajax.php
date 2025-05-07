<?php
    include "../../functions/main_config.php";
    include "service.php";

    $AgentService = new AgentService;

    $action          = $_GET["action"];
    $option_selected = $_GET["option_selected"];
    $id_agent        = $_POST["idAgenciador"];
?>