<?php
    class GenericService {
        /****************************************************************************
        * Funções de manipulação de permissões
        ******************************************************************************/

        function getPermissions($id_user) {
            global $data_db;

            $sql_data_permission = $data_db->executeQuery(
                "SELECT p.idAgenciador, inclusao, alteracao, exclusao, a.codigo, a.id AS idAtividade,
                        a.nome, a.descricao, a.link, a.idMenu 
                    FROM permissao p
                INNER JOIN atividade a ON a.id = p.idAtividade
                WHERE p.idUsuario = $id_user"
            );

            while($row = $data_db->getLine($sql_data_permission)) {
                $_SESSION["pode_acessar"][$row["codigo"]][$row["idAgenciador"]] = $row;

                if($row["link"] != "") {
                    $_SESSION["pode_acessar"][$row["link"]][$row["idAgenciador"]] = $row;
                }
            }
        }

        /****************************************************************************
        * Funções de manipulação do Login
        ******************************************************************************/

        function getUser($data_post) {
            global $data_db;
            $db = $data_db->connection();

            $code_user = $db->real_escape_string($data_post["user"]);
            $password  = $db->real_escape_string($data_post["pass"]);
    
            $sql_data_user = $data_db->executeGetLine("SELECT * FROM usuario WHERE codigo = '$code_user' AND ativo = 'S'");

            return ["user" => $code_user, "pass" => $password, "query" => $sql_data_user];
        }
    
        function getPermission($id_user, $id_agenciador) {
            global $data_db;

            $sql_data_permission = $data_db->executeQuery("SELECT * FROM permissao WHERE idUsuario = $id_user AND idAgenciador = $id_agenciador");
            
            return $sql_data_permission->num_rows > 0 ? true : false;
        }
    
        function getAgent($id_agenciador) {
            global $data_db;

            $sql_data_agenciador = $data_db->executeGetLine("SELECT * FROM agenciador WHERE id = $id_agenciador");
            $_SESSION["agent"] = $sql_data_agenciador;
        }

        function getAllAgents() {
            global $data_db;
            
            return $data_db->executeQuery("SELECT * FROM agenciador WHERE ativo = 'S' ORDER BY nome");
        }

        /****************************************************************************
        * Funções de manipulação de select
        ******************************************************************************/

        function listComboAgent($id_activitie, $standard) {
            global $data_db;
            
            $id_user = $_SESSION["user"]["id"];

            $data_db->listComboDistinct("agenciador", "agenciador.id", "nome", $standard,
            "INNER JOIN permissao p ON p.idAgenciador = agenciador.id
            WHERE p.idUsuario = $id_user AND p.idAtividade = $id_activitie AND p.inclusao = 'S' 
            ORDER BY nome");
        }

        function getAllAgentsPermissionPage() {
            global $data_db;

            $id_user = $_SESSION["user"]["id"];
        
            $sql_data_agents = $data_db->executeGetLine(
                "SELECT p.idAgenciador, GROUP_CONCAT(idAgenciador SEPARATOR ', ') AS Agenciadores 
                    FROM permissao p
                INNER JOIN atividade a ON a.id = p.idAtividade
                WHERE p.idUsuario = $id_user AND a.codigo = 'CRUD_PERMISSIONS'"
            );
            
            return $sql_data_agents["Agenciadores"] ? $sql_data_agents["Agenciadores"] : "NULL";
        }

        /****************************************************************************
        * Funções de manipulação do filtro da sessão
        ******************************************************************************/

        function getAgentSession($id_agent) {
            global $data_db;

            return $data_db->executeGetLine("SELECT * FROM agenciador WHERE id = ".$id_agent);
        }

        function getUserSession($id_user) {
            global $data_db;

            return $data_db->executeGetLine("SELECT * FROM usuario WHERE id = ".$id_user);
        }

        function getPeriodSession($id_period) {
            global $data_db;

            return $data_db->executeGetLine("SELECT * FROM periodo WHERE id = ".$id_period);
        }

        function getPermissionsOnAgents($code_activitie) {
            global $data_db;
            
            $ids_formated = "";
            $ids_agents = [];

            $id_user = $_SESSION["user"]["id"];

            $sql_data_permissions = $data_db->executeQuery(
                "SELECT p.idAgenciador 
                    FROM permissao p
                INNER JOIN atividade a ON a.id = p.idAtividade
                WHERE p.idUsuario = $id_user AND a.codigo = '$code_activitie' 
                ORDER BY p.idAgenciador"
            );
            
            $total_data = $data_db->linesAccount($sql_data_permissions);

            if($total_data > 0) {
                while($row = $data_db->getLine($sql_data_permissions)) {
                    $ids_agents[] = $row["idAgenciador"];
                }
            
                $ids_formated = implode(", ", $ids_agents);
            }
            
            return $ids_formated;
        }

        function getAgentsByIds($ids_formatated, $order = false) {
            global $data_db;

            $order_by = $order ? "ORDER BY id ASC" : "";

            return $data_db->executeQuery("SELECT id, nome FROM agenciador WHERE id IN($ids_formatated) $order_by");
        }
    }
?>