<?php
    class ActivitieService {
        function getAllActivities() {
            global $data_db;
            
            $sql_data_activities = $data_db->executeQuery(
                "SELECT CONCAT('row_', a.id) AS DT_RowId, a.codigo, a.nome, 
                        a.descricao, m.descricao AS descricaoMenu, a.ordem, a.link
                    FROM atividade a
                LEFT OUTER JOIN menu m ON m.id = a.id"
            );
    
            return $data_db->getAllLines($sql_data_activities);
        }

        function deleteActivitiePermissions($id) {
            global $data_db;

            $data_db->executeQuery("DELETE FROM permissao WHERE idAtividade IN ($id)");
        }
    }
?>