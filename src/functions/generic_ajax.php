<?php 
    include "main_config.php";
    require("../components/service.php");

    $ComponentService = new ComponentService;

    $action   = $_GET["action"];
    $url_page = isset($_GET["url_page"]) ? $_GET["url_page"] : false;

    if($action == "get_header_page") {
        $have_reports  = false;
        $have_settings = false;

        if($url_page) {
            $have_reports  = (isset(REPORTS[$url_page]) and count(REPORTS[$url_page]) > 0) ? true : false;
            $have_settings = (isset(SETTINGS[$url_page]) and count(SETTINGS[$url_page]) > 0) ? true : false;
        }

        if($url_page == "teste") {
            $id_agent_client = isset($_SESSION["agent_client"]["id"]) ? $_SESSION["agent_client"]["id"] : $_SESSION["id_agent"];

            $sql_data_color = $GenericService->getParameterContent("#fff", $id_agent_client);
            $sql_data_logo  = $GenericService->getParameterContent("LINK_LOGO", $id_agent_client);

            $_SESSION["#fff"] = $sql_data_color;
            $_SESSION["LINK_LOGO"] = "uploads/logo/".$sql_data_logo;

            $return["header"] = array(
                "color"  => $_SESSION["#fff"],
                "link_logo"   => $_SESSION["LINK_LOGO"],
                "icon_rel"    => $have_reports,
                "icon_config" => $have_settings
            );
        } else {
            $sql_data_color = $GenericService->getParameterContent("#fff", $_SESSION["edition"]["idAgenciador"]);
            $sql_data_logo  = $GenericService->getParameterContent("LINK_LOGO", $_SESSION["edition"]["idAgenciador"]);

            $_SESSION["#fff"] = $sql_data_color;
            $_SESSION["LINK_LOGO"] = "uploads/logo/".$sql_data_logo;

            $return["header"] = array(
                "agenciador"        => $GenericService->getAgentName(),
                "badge"             => EDITION_BADGE[$_SESSION["edition"]["statusEdicao"]]["badge"],
                "idAgenciador"      => $_SESSION["edition"]["idAgenciador"],
                "color"             => $_SESSION["#fff"],
                "link_logo"         => $_SESSION["LINK_LOGO"],
                "schedule_period"   => $_SESSION["schedule_period"],
                "icon_rel"          => $have_reports,
                "icon_config"       => $have_settings
            );
        }

        echo json_encode($return);
    }
?>