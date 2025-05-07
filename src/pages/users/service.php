<?php
    class UserService {
        function getAllUsers() {
            global $data_db;
            
            $sql_data_users = $data_db->executeQuery(
                "SELECT CONCAT('row_', id) AS DT_RowId, codigo, email, tipoUsuario,
                        nome, dataNascimento, IF(ativo = 'S', 'Sim', 'Não') AS ativo, id,
                        IF(modelo = 'S', 'Sim', 'Não') as modelo
                    FROM usuario"
            );
    
            $data_response = [];

            while($row = $data_db->getLine($sql_data_users)) {
                $row["tipoUsuario"] = TYPE_USER[$row["tipoUsuario"]];
                $data_response[] = $row;
            }

            return $data_response;
        }

        function deletePermissionsUser($id) {
            global $data_db;

            $data_db->executeQuery("DELETE FROM permissao WHERE idUsuario IN ($id)");
        }
    }
?>