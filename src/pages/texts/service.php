<?php
    class TextService {
        function getAllTexts() {
            global $data_db;
            
            $sql_data_texts = $data_db->executeQuery(
                "SELECT CONCAT('row_', t.id) as DT_RowId, t.id, t.codigo, t.nome, t.descricao
                    FROM texto t"
            );

            $data_response = [];
    
            while($row = $data_db->getLine($sql_data_texts)) {
                $row["nome"];
                $row["codigo"];
                $row["descricao"];
                
                $data_response[] = $row;
            }

            return $data_response;
        }

        function getTextByID($id_text) {
            global $data_db;

            return $data_db->executeGetLine("SELECT * FROM texto WHERE id = $id_text");
        }
    }
?>