<?php
    /****************************************************************************
    * Funções de manipulação de edição e agenciador
    ******************************************************************************/
    
    if(isset($_GET["id_user_client"])) {
        if($_GET["id_user_client"] != "") {
            $sql_data_user_client = $GenericService->getUserSession($_GET["id_user_client"]);

            $_SESSION["user_client"] = $sql_data_user_client;
        } else {
            unset($_SESSION["user_client"]);
        }
    }

    if(isset($_GET["schedule_period"])) {
        if($_GET["schedule_period"] == "") {
            unset($_SESSION["schedule_period"]);
        } else {
            $_SESSION["schedule_period"] = $_GET["schedule_period"];
        }
    }

    if(isset($_GET["id_agent"])) {
        $sql_data_agent = $GenericService->getUserSession($_GET['id_agent']);
        $_SESSION["agent"] = $sql_data_agent;
    }

    /****************************************************************************
    * Funções de manipulação filtros tela de clientes
    ******************************************************************************/

    if(isset($_GET["status_client_active"])) {
        if($_GET["status_client_active"] == "") {
            unset($_SESSION["status_client_active"]);
        } else {
            $_SESSION["status_client_active"] = $_GET["status_client_active"];
        }
    }

    if(isset($_GET["status_client_inactive"])) {
        if($_GET["status_client_inactive"] == "") {
            unset($_SESSION["status_client_inactive"]);
        } else {
            $_SESSION["status_client_inactive"] = $_GET["status_client_inactive"];
        }
    }

    /****************************************************************************
    * Funções de manipulação filtros tela de agendamentos
    ******************************************************************************/

    if(isset($_GET["status_schedule_served"])) {
        if($_GET["status_schedule_served"] == "") {
            unset($_SESSION["status_schedule_served"]);
        } else {
            $_SESSION["status_schedule_served"] = $_GET["status_schedule_served"];
        }
    }

    if(isset($_GET["status_schedule_pending"])) {
        if($_GET["status_schedule_pending"] == "") {
            unset($_SESSION["status_schedule_pending"]);
        } else {
            $_SESSION["status_schedule_pending"] = $_GET["status_schedule_pending"];
        }
    }

    if(isset($_GET["ignore_empty_dates"])) {
        if($_GET["ignore_empty_dates"] == "") {
            unset($_SESSION["ignore_empty_dates"]);
        } else {
            $_SESSION["ignore_empty_dates"] = $_GET["ignore_empty_dates"];
        }
    }
?>