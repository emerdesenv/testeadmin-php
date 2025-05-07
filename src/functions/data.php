<?php
    //Classe de Manipulação do Banco de Dados

    /****************************************************************************
    * Configurações de variáveis globais da conexão
    ******************************************************************************/

    if(!defined("MYSQL_HOST")) {
        DEFINE("MYSQL_HOST", getenv("MYSQL_HOST"));
        DEFINE("MYSQL_ROOT_PASSWORD", getenv("MYSQL_ROOT_PASSWORD"));
        DEFINE("MYSQL_DATABASE", getenv("MYSQL_DATABASE"));
        DEFINE("MYSQL_USER", getenv("MYSQL_USER"));
        DEFINE("MYSQL_PASSWORD", getenv("MYSQL_PASSWORD"));
        DEFINE("APP_TITLE", getenv("APP_TITLE"));
        DEFINE("LOG_DB", getenv("LOG_DB"));
    }

    class Data {
        var $message_error = "";
        var $query         = null;
        var $con           = null;
        var $server        = MYSQL_HOST;
        var $name_db       = MYSQL_DATABASE;
        var $user_db       = MYSQL_USER;
        var $pass_db       = MYSQL_PASSWORD;
        var $no_quotes     = array("int", "tinyint", "smallint", "mediumint", "bigint", "boolean");

        /****************************************************************************
        * Configurações de conexão
        ******************************************************************************/

        function __destruct() {
            $this->closeConnection();
        }

        function closeConnection(): void {
            if ($this->con) {
                mysqli_close($this->con);
                $this->con = null;
            }
        }

        function connection() {
            return $this->con;
        }

        //Faz a conexão com o banco de dados do tipo passado
        function conectDataBase($name_db) {
            $this->name_db = $name_db;
            $this->conectMySQL();
        }

        //Conecta ao banco de dados MySQL pelas variáveis definidas;
        function conectMySQL() {
            $this->message_error = "";

            if(!$this->con = mysqli_connect($this->server, $this->user_db, $this->pass_db, $this->name_db)) {
                $this->message_error = "<h5>Erro de conexão com o banco de dados</h5>\n";
                $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ocorreu um erro ao conectar ao ";
                $this->message_error .= "banco de dados MySQL. O servidor retornou o código: ".mysqli_connect_errno().PHP_EOL."</p>\n";
                $this->message_error .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A mensagem retornada pelo servidor foi:</p>\n";
                $this->message_error .= "<p class='error-server'>".mysqli_connect_error().PHP_EOL."</p>";

                return false;
            }

            mysqli_set_charset($this->con, 'utf8mb4');
            return true;
        }

        /****************************************************************************
        * Configurações do CRUD (INSERT, UPDATE e DELETE)
        ******************************************************************************/

        function insertRegister($table, $elements, $return_last_id = false, $auto_increment_false = false) {
            $this->message_error = "";

            if($this->tableExists($table)) {
                $fileds = $values = "";
                $list   = $this->fieldsList($table);

                while($field = $this->getLine($list)) {
                    $field_exists = false;

                    if($auto_increment_false || $field["Extra"] != 'auto_increment') {
                        $field_exists = array_key_exists($field["Field"], $elements);
                    }

                    if($field_exists) {

                        if(!($field["Default"] == NULL and $elements[$field["Field"]] == "")) {
                            if($fileds != "") $fileds .= ",";

                            $fileds .= $field["Field"];

                            if($values != "") {
                                $values .= "','";
                            } else {
                                $values = "'";
                            }

                            $values .= $elements[$field["Field"]];
                        }
                    }
                }
                
                $values .= "'";
                $this->query = "INSERT INTO $table ($fileds) VALUES ($values)";

                $insert_return = $this->executeQuery($this->query, $return_last_id);

                if($insert_return) {
                    return $insert_return;
                } else {
                    ob_start();
                    var_dump($elements);
                    $dump = ob_get_contents();
                    ob_end_clean();

                    $this->message_error = "<h5>Erro ao Gravar os Dados</h5>\n";
                    $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ocorreu um erro ao gravar as ";
                    $this->message_error .= "informações no banco de dados MySQL. O servidor retornou o código: ".mysqli_errno($this->con)."</p>\n";
                    $this->message_error .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A mensagem retornada pelo servidor foi:</p>\n";
                    $this->message_error .= "<p class='error-server'>".mysqli_error($this->con)."</p>";
                    $this->message_error .= "<p class='error-server'>Os dados passados para gravação foram: ".$dump." e a instrução SQL passada foi: ".$this->query."</p>";

                    return false;
                }
            } else {
                ob_start();
                var_dump($elements);
                $dump = ob_get_contents();
                ob_end_clean();

                $this->message_error = "<h5>Erro ao Gravar os Dados</h5>\n";
                $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ocorreu um erro ao gravar as ";
                $this->message_error .= "informações no banco de dados MySQL. O servidor retornou o código: ".mysqli_errno($this->con)."</p>\n";
                $this->message_error .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A mensagem retornada pelo servidor foi:</p>\n";
                $this->message_error .= "<p class='error-server'>A tabela '".$table."' não foi encontrada em '".$this->name_db."'</p>";
                $this->message_error .= "<p class='error-server'>Os dados passados para gravação foram: ".$dump."</p>";

                return false;
            }
        }
        
        //Processa alterações nos registros de uma tabela
        function updateRegister($table, $elements, $restriction) {
            $this->message_error = "";

            if($this->tableExists($table)) {
                $fileds = "";
                $list   = $this->fieldsList($table);

                while($field = $this->getLine($list)) {

                    if(array_key_exists($field["Field"], $elements)) {
                        
                        if($field["Default"] == NULL and $elements[$field["Field"]] == "") { 
                            $value_field = "NULL";
                        } else {
                            if(in_array(explode("(", $field["Type"])[0], $this->no_quotes)) {
                                $value_field = $elements[$field["Field"]];
                            } else {
                                $value_field = "'".$elements[$field["Field"]]."'";
                            }
                        }
                        
                        if($fileds != "") {
                            $fileds .= ",";
                        }
                        
                        $fileds .= $field["Field"]."=".$value_field;                         
                    }
                }

                $this->query = "UPDATE $table SET $fileds WHERE $restriction";

                if($this->executeQuery($this->query)) {
                    return true;
                } else {
                    ob_start();
                    var_dump($elements);
                    $dump = ob_get_contents();
                    ob_end_clean();

                    $this->message_error = "<h5>Erro ao Gravar os Dados</h5>\n";
                    $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ocorreu um erro ao gravar as ";
                    $this->message_error .= "informações no banco de dados MySQL. O servidor retornou o código: ".mysqli_errno($this->con)."</p>\n";
                    $this->message_error .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A mensagem retornada pelo servidor foi:</p>\n";
                    $this->message_error .= "<p class='error-server'>".mysqli_error($this->con)."</p>";
                    $this->message_error .= "<p class='error-server'>Os dados passados para gravação foram: ".$dump." e a instrução SQL passada foi: ".$this->query."</p>";

                    return false;
                }
            } else {
                ob_start();
                var_dump($elements);
                $dump = ob_get_contents();
                ob_end_clean();

                $this->message_error = "<h5>Erro ao Gravar os Dados</h5>\n";
                $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ocorreu um erro ao gravar as ";
                $this->message_error .= "informações no banco de dados MySQL. O servidor retornou o código: ".mysqli_errno($this->con)."</p>\n";
                $this->message_error .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A mensagem retornada pelo servidor foi:</p>\n";
                $this->message_error .= "<p class='error-server'>A tabela '".$table."' não foi encontrada em '".$this->name_db."'</p>";
                $this->message_error .= "<p class='error-server'>Os dados passados para gravação foram: ".$dump."</p>";

                return false;
            }
        }

        function deleteRegister($id, $table) {
            $this->executeQuery("DELETE FROM $table WHERE id IN ($id)");
        }

        /****************************************************************************
        * Configurações de funções de busca de dados
        ******************************************************************************/

        //Retorna todas os resultados de uma query em formato array
        function getAllLines($search_identifier) {
            $list_lines = [];

            while($row = mysqli_fetch_array($search_identifier, MYSQLI_ASSOC)) {
                $list_lines[] = $row;
            }

            return $list_lines;        
        }
        
        //Retorna uma linha do resultado de uma busca ao banco de dados
        function getLine($search_identifier, $tolerate_error = false) {

            if($ok = mysqli_fetch_array($search_identifier, MYSQLI_ASSOC)) {
                return $ok;
            } else if(mysqli_errno($this->con) === 0) {
                return false;
            } else {
                if($tolerate_error) {
                    return false;
                } else {
                    $this->message_error = "<h5>Erro ao Processar Solicitação</h5>\n";
                    $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ocorreu um erro ao processar a ";
                    $this->message_error .= "solicitação no banco de dados MySQL. O servidor retornou o código: ".mysqli_errno($this->con)."</p>\n";
                    $this->message_error .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A mensagem retornada pelo servidor foi:</p>\n";
                    $this->message_error .= "<p class='error-server'>".mysqli_error($this->con)."</p>";
                    $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O comando de solicitação SQL enviado ";
                    $this->message_error .= "ao banco foi: <br>".$this->query."</p>";
                    $this->message_error .= "<p>Página: ".$_SERVER['PHP_SELF']."</p>";

                    if(LOG_DB) {
                        die($this->message_error);
                    }
                }
            }
        }
        
        //Retorna apenas 1 linha de resultados, porque faz a busca com LIMIT 1
        function executeGetLine($comand = "") {
            if($ok = $this->executeQuery($comand)) {
                if($ok = $this->getLine($ok, true)) {
                    if($ok != "ERRO") {
                        return $ok;
                    } else if(LOG_DB) {
                        die ("Busca Linha:<br>".$this->message_error);
                    }
                } else {
                    return false;
                }
            } else if(LOG_DB) {
                die ("Executa Query:<br>".$this->message_error);
            }

            return false;
        }

        //Executa a query definida no sistema ou a passada por parêmetro
        function executeQuery($comand = "", $return_last_id = false) {
            while($this->con == null) $this->conectDataBase($this->name_db);

            $comand = empty($comand) ? $this->query : $comand;
            $ok = mysqli_query($this->con, $comand);

            if($ok !== false) {
                if($return_last_id) {
                    $return = mysqli_insert_id($this->con);
                } else {
                    $return = $ok;
                }  
            } else {
                $this->message_error = "<h5>Erro ao Processar Solicitação</h5>\n";
                $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ocorreu um erro ao processar a ";
                $this->message_error .= "solicitação no banco de dados MySQL. O servidor retornou o código: ".mysqli_errno($this->con)."</p>\n";
                $this->message_error .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A mensagem retornada pelo servidor foi:</p>\n";
                $this->message_error .= "<p class='error-server'>".mysqli_error($this->con)."</p>";
                $this->message_error .= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O comando de solicitação SQL enviado ";
                $this->message_error .= "ao banco foi: <br>".$comand."</p>";
                $this->message_error .= "<p>Página: ".$_SERVER['PHP_SELF']."</p>";

                if(LOG_DB) {
                    die($this->message_error);
                }

                $return = false;
            }

            $pos = strpos(strtolower($comand), "select");

            if($pos === false) {
                $pos = strpos(strtolower($comand), "show tables");
                if($pos === false) {
                    $pos = strpos(strtolower($comand), "show fields");
                    if($pos === false) {
                        $validation = explode("/", $_SERVER["REQUEST_URI"]);

                        $uri     = isset($validation[2]) ? $validation[2] : $validation[1];
                        $comand  = addslashes($comand);
                        $metodo  = substr($comand, 0, 6);

                        mysqli_query($this->con, "INSERT INTO log (codigo, url, dados, metodo) VALUES ('{$_SESSION["user"]["codigo"]}', '$uri', '$comand', '$metodo')");
                    }
                }
            }    

            return $return;
        }

        /****************************************************************************
        * Configurações de funções genéricas (contador, lista, existe, etc)
        ******************************************************************************/

        function linesAccount($queryResult) {
            if($this->message_error == "") {
                return mysqli_num_rows($queryResult);
            } else {
                return false;
            }
        }

        function fieldsList($table) {
            if($this->tableExists($table)) {
                return $this->executeQuery("SHOW FIELDS FROM $table");
            }
                
            return false;
        }

        function listTables() {
            return $this->executeQuery("SHOW TABLES FROM ".$this->name_db);
        }

        function tableExists($table) {
            $list = $this->listTables();

            while($atual = mysqli_fetch_array($list)) {
                if(strtolower($atual[0]) == strtolower($table)) {
                    return true;
                }
            }
                
            return false;
        }

        function checkDuplicateKey($table, $field, $value, $id_form = false) {
            $condition = "";
    
            if($id_form) {
                $condition = "id != $id_form AND ";
            }

            if(is_array($field)) {
                $str = [];
                
                foreach($field as $f) {
                    $str[] = "$f = '{$value[$f]}'";
                }

                $str = implode(" AND ", $str);
    
                $query = "SELECT id FROM $table WHERE $condition $str";
            } else {
                $query = "SELECT $field FROM $table WHERE $condition $field = '$value'";
            }

            $data = $this->executeGetLine($query);
    
            return $data;
        }
    
        // Retorna a linha do banco para edição em formulários ajax
        function loadGenericObject($id = false, $generic = false, $ignore_validation = false) {
            $return = false;

            if(!$generic) $generic = explode("/", $_SERVER["REQUEST_URI"])[2];
    
            if($id) {
                if(!validateAccess($generic, "alteracao") and !$ignore_validation) {
                    die("<span class='text-danger'>Acesso negado</span>");
                }
    
                $return = $this->executeGetLine("SELECT * FROM $generic WHERE id = $id");
    
            } else {
                if(!validateAccess($generic, "inclusao") and !$ignore_validation) {
                    die("<span class='text-danger'>Acesso negado</span>");
                }
            }
    
            return $return;
        }

        function listComboDistinct($table, $key, $description, $standard = "", $ordination = "") {
            $sql_data_table = $this->executeQuery("SELECT DISTINCT $key, $description FROM $table $ordination");

            while($row = $this->getLine($sql_data_table)) {
                echo "\t\t\t\t<option value='".((strpos($key, '.') > 0) ? 
                $row[substr(strstr($key, '.') , 1)] : $row[$key])."'".(($standard == ((strpos($key, '.') > 0) ? 
                $row[substr(strstr($key, '.') , 1)] : $row[$key])) ? (" selected"):"").">".$row[$description]."</option>\n";
            } 

            return true;
        }
    
        function listCombo($table, $key, $description, $standard = "", $ordination = "", $attributes = false) {
            $attrs = "";
    
            $sql_data_table = $this->executeQuery("SELECT * FROM $table $ordination");

            while($row = $this->getLine($sql_data_table)) {
    
                if($attributes) {
                    $attrs = "";

                    foreach($attributes as $arr) {
                        $attrs .= "$arr='{$row[$arr]}' ";
                    }
                }
    
                echo "\t\t\t\t<option $attrs value='".((strpos($key, '.') > 0) ? 
                $row[substr(strstr($key,'.'), 1)] : $row[$key])."'".(($standard == ((strpos($key,'.') > 0 ) ? 
                $row[substr(strstr($key,'.'), 1)] : $row[$key])) ? (" selected") : "").">".$row[$description]."</option>\n";
            }
        }

        function listComboActive($table, $key, $description, $standard = false, $complement = "", $attributes = false) {

            $attrs = ""; $active = $selected = false; $active_group = null;

            $actives = [
                "S" => ["label" => "Ativos", "disabled" => ""],
                "N" => ["label" => "Inativos", "disabled" => "disabled"]
            ];
    
            $field = $this->fieldsList($table);

            while($row = $this->getLine($field)) {
                if($row["Field"] == "ativo") {
                    $active = true;
                }
            }
    
            if($active) {
                if(strpos(strtolower($complement), "order by") !== false) {
                    $complement = str_ireplace("order by", "ORDER BY ativo DESC, ", $complement);
                } else {
                    $complement .= "ORDER BY ativo DESC";
                }
                
                if(!$standard) {
                    if(strpos(strtolower($complement), "where") !== false) {
                        $complement = str_ireplace("where", "WHERE ativo = 'S' AND ", $complement);
                    } else {
                        $complement = "WHERE ativo = 'S'".$complement;
                    }   
                }
            }
    
            $sql = $this->executeQuery("SELECT * FROM $table $complement");
    
            while($row = $this->getLine($sql)) {
    
                $selected = "";
    
                if($attributes) {
                    $attrs = "";
                    foreach($attributes as $arr) $attrs .= "$arr='{$row[$arr]}' ";
                }
    
                if($standard) {
                    if(is_array($standard)) {
                        $selected = in_array($row[$key], $standard) ? "selected" : "";
                    } else {
                        $selected = ($row[$key] == $standard) ? "selected" : "";
                    }
                }
    
                if($active and $standard) {
                    if($active_group == null || $row["ativo"] != $active_group) {
                        if($active_group != null) {
                            echo "\t</optgroup>\n\t";
                        }

                        echo "<optgroup label='{$actives[$row["ativo"]]["label"]}'>\n";
                        $active_group = $row["ativo"];
                    }
                }
                
                $disabled = $actives[$row["ativo"]]["disabled"];
                echo "\t\t<option $attrs $selected $disabled value='{$row[$key]}'>{$row["id"]} - {$row[$description]}</option>\n";
            }
    
            if($active_group != null)
            echo "\t</optgroup>\n";
        }
    }
?>