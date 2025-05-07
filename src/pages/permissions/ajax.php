<?php
    include "../../functions/main_config.php";
    include "service.php";

    $PermissionService = new PermissionService;

    $action = $_GET["action"];

    if($action == "list_activities") {
        $id_user  = $_GET["idUsuario"];
        $id_agent = $_GET["idAgenciador"];
       
        $sql_data_activities = $PermissionService->getAllActivities($id_user, $id_agent);
    
        echo json_encode($sql_data_activities);
    }
?>