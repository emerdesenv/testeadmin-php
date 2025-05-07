<?php 
    class ComponentService {
        /****************************************************************************
        * Funções de manipulação de filtros do modal
        ******************************************************************************/

        function listAgentsModal($ids, $agent_dash = false, $cond_select = false) {
            global $data_db;

            $data_select = "";
            $id_select   = "";

            $ids = implode(",", $ids);
            $sql_data_agents = $data_db->executeQuery("SELECT * FROM agenciador WHERE id IN ($ids) ORDER BY nome");

            while($row = $data_db->getLine($sql_data_agents)) {
                if($cond_select) {
                    $id_select = isset($agent_dash["id"]) ? $agent_dash["id"] : "";
                } else {
                    $id_select = $_SESSION["agent"]["id"];
                }

                $data_select .= "<option value='".$row["id"]."' ".($row["id"] == $id_select ? "selected" : "").">".$row["nome"]."</option>";
            }

            return $data_select;
        }

        /****************************************************************************
        * Funções de manipulação do menu
        ******************************************************************************/
        
        function listMenu() {
            global $data_db;

            return $data_db->executeQuery("SELECT * FROM menu WHERE 1 ORDER BY ordem ASC");
        }

        function listSubMenu($id_menu) {
            global $data_db;

            return $data_db->executeQuery("SELECT * FROM atividade WHERE idMenu = $id_menu ORDER BY ordem, nome ASC");
        }
     }
?>