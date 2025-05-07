<?php 
    require("service.php");

    $ComponentService = new ComponentService;

    /****************************************************************************
    * Funções de manipulação de filtros do modal
    ******************************************************************************/

    function listAgentsModal($agent_param = false, $cond_select = false) {
        $ids    = [];
        $return = "";

        global $ComponentService;
                           
        foreach($_SESSION["pagina_atual"] as $permission) {
            $ids[] = $permission["idAgenciador"];
        }

        $return .= $ComponentService->listAgentsModal($ids, $agent_param, $cond_select);

        return $return;
    }
?>