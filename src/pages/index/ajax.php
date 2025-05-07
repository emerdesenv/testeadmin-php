<?php
    include "../../functions/main_config.php";
    include "service.php";

    $SchedulingService = new SchedulingService;

    $action = $_GET["action"];

    if($action == "search_client") {
        $array_return = $SchedulingService->getAllClientsByKey($_GET["term"]);

        echo json_encode($array_return);
    }
?>