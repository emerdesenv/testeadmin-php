<?php 
    class PermissionService {
        function getAllPermissions() {
            global $data_db;

            $id_user = $_SESSION["user"]["id"];

            $sql_data_agents = $data_db->executeGetLine(
                "SELECT p.idAgenciador, GROUP_CONCAT(idAgenciador SEPARATOR ', ') AS Agenciadores 
                    FROM permissao p
                INNER JOIN atividade a ON a.id = p.idAtividade
                WHERE p.idUsuario = $id_user AND a.codigo = 'CRUD_PERMISSIONS'"
            );
            
            $agents_formated = $sql_data_agents["Agenciadores"] ? $sql_data_agents["Agenciadores"] : "NULL";

            $sql_data_permission = $data_db->executeQuery(
                "SELECT p.id, CONCAT('row_', p.id) AS DT_RowId, a.nome, u.codigo, descricao, 
                        IF(inclusao = 'S', 'Sim', 'Não') AS inclusao,
                        IF(alteracao = 'S', 'Sim', 'Não') AS alteracao,
                        IF(exclusao = 'S', 'Sim', 'Não') AS exclusao
                FROM permissao p
                INNER JOIN atividade a ON a.id = p.idAtividade 
                INNER JOIN agenciador ag ON ag.id = p.idAgenciador
                INNER JOIN usuario u ON u.id = p.idUsuario
                WHERE p.idAgenciador IN ($agents_formated)"
            );

            return $data_db->getAllLines($sql_data_permission);
        }

        function getAllActivities($id_user, $id_agent) {
            global $data_db;
            
            $sql_data_activities = $data_db->executeQuery(
                "SELECT id, codigo, nome, descricao 
                    FROM atividade a
                WHERE id NOT IN (SELECT idAtividade FROM permissao WHERE idUsuario = $id_user AND idAgenciador = $id_agent) 
                ORDER BY descricao"
            );

            return $data_db->getAllLines($sql_data_activities);
        }

        function copyPermissionsByModel($id_agent, $by, $for) {
            global $data_db;

            $sql_data_permissions = $data_db->executeQuery(
                "INSERT IGNORE INTO permissao
                    SELECT NULL, idAtividade, $for AS idUsuario, idAgenciador, inclusao, alteracao, exclusao
                    FROM permissao
                WHERE idUsuario = $by AND idAgenciador = $id_agent
                GROUP BY idAtividade"
            );

            $this->deleteDuplicatePermissions();

            return $sql_data_permissions;
        }

        function deleteDuplicatePermissions() {
            global $data_db;

            $sql_data_permissions = $data_db->executeQuery(
                "SELECT id, idAtividade, idUsuario, idAgenciador
                    FROM permissao
                GROUP BY idAtividade, idUsuario, idAgenciador
                HAVING COUNT(*) > 1"
            );

            while($row = $data_db->getLine($sql_data_permissions)) {
                $data_db->executeQuery("DELETE FROM permissao WHERE id = {$row["id"]}");
            }
        }
    }
?>