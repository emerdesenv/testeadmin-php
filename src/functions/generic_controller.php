<?php
    require("generic_service.php");

    $GenericService = new GenericService;

    /****************************************************************************
    * Funções de manipulação de ações que a tela possui
    ******************************************************************************/

    function validateURL($url, $key) {
        if(!$url) {
            $validation = explode("/", $_SERVER["REQUEST_URI"]);
            $url = end($validation);
        }

        return in_array($url, $key);
    }

    function vTable($url = false) {
        return validateURL($url, TABLE_LIST_PAGES);
    }

    function vBttAdd($url = false) {
        return validateURL($url, BT_ADD);
    }

    function vModal($url = false) {
        return validateURL($url, MODAL);
    }

    function vGraphics($url = false) {
        return validateURL($url, GRAPHICS);
    }

    function vTotals($url = false) {
        return validateURL($url, TOTALS);
    }

    function vFilters($url = false) {
        return validateURL($url, FILTERS);
    }

    function getURL() {
        $validation = explode("/", $_SERVER["REQUEST_URI"]);
        $all = end($validation);
        return explode("?", $all)[0];
    }

    function pageInfo($what) {
        if(!isset($_SESSION["pode_acessar"])) {
            loadPermissions();
        }

        $has_access = $_SESSION["pode_acessar"][$what];

        if(isset($has_access)) {
            foreach($has_access as $arr) {
                return $arr;
            }
        } else {
            return false;
        }
    }

    function skipValidation($code_activitie, $page_url) {
        $code_activitie = isset($code_activitie) ? $code_activitie : false;
        $page           = $_SESSION["pagina_atual"] = validateAccess($code_activitie);

        if(!$page) {
            $redir = $_SESSION["logged"] ? "denied" : "login";
            header("Location: $redir");
        }
        
        $info = pageInfo($page_url);

        return ["page" => $page, "info" => $info, "code_activitie" => $code_activitie];
    }

    /****************************************************************************
    * Funções de configurações do dispositivo
    ******************************************************************************/

    function getOS() {
        global $user_agent;

        $os_platform  = "";

        $os_array =
            array(
                '/windows nt 10/i'      =>  'windows',
                '/windows nt 6.3/i'     =>  'windows',
                '/windows nt 6.2/i'     =>  'windows',
                '/windows nt 6.1/i'     =>  'windows',
                '/windows nt 6.0/i'     =>  'windows',
                '/windows nt 5.2/i'     =>  'windows',
                '/windows nt 5.1/i'     =>  'windows',
                '/windows xp/i'         =>  'windows',
                '/windows nt 5.0/i'     =>  'windows',
                '/windows me/i'         =>  'windows',
                '/win98/i'              =>  'windows',
                '/win95/i'              =>  'windows',
                '/win16/i'              =>  'windows',
                '/macintosh|mac os x/i' =>  'mac',
                '/mac_powerpc/i'        =>  'mac',
                '/linux/i'              =>  'linux',
                '/ubuntu/i'             =>  'linux',
                '/iphone/i'             =>  'iphone',
                '/ipod/i'               =>  'ipod',
                '/ipad/i'               =>  'ipad',
                '/android/i'            =>  'android',
                '/blackberry/i'         =>  'blackberry',
                '/webos/i'              =>  'mobile'
            );

        foreach($os_array as $regex => $value) {
            if(preg_match($regex, $user_agent)) {
                $os_platform = $value;  
            }
        }
            
        return $os_platform;
    }

    function getBrowser() {
        global $user_agent;

        $browser = "Unknown Browser";

        $browser_array =
            array(
                '/msie/i'      => 'ie',
                '/firefox/i'   => 'firefox',
                '/safari/i'    => 'safari',
                '/chrome/i'    => 'chrome',
                '/edge/i'      => 'edge',
                '/opera/i'     => 'opera',
                '/netscape/i'  => 'netscape',
                '/maxthon/i'   => 'maxthon',
                '/konqueror/i' => 'konqueror',
                '/mobile/i'    => 'handheld_browser'
            );

        foreach($browser_array as $regex => $value) {
            if(preg_match($regex, $user_agent)) {
                $browser = $value;
            }       
        }

        return $browser;
    }

    /****************************************************************************
    * Função para validações de Campos
    ******************************************************************************/

    function validateBirthClient($birth) {
        $dt_formated = new DateTime($birth);
        $now         = new DateTime(date("Y-m-d"));
        $diff        = $dt_formated->diff($now);
    
        if($diff->y < 16) {
            return false;
        }

        return true;
    }

    function validateCPF($value_param) {
        $c  = substr($value_param, 0, 9);
        $dv = substr($value_param, 9, 2);
        $d1 = 0;

        for($i = 0; $i < 9; $i++) {
            $d1 += charAt($c, $i)*(10-$i);
        }
        
        if($d1 == 0) return false;
    
        $d1 = 11 - ($d1 % 11);
        
        if($d1 > 9) $d1 = 0;
        if(charAt($dv, 0) != $d1) {
            return false;
        }
    
        $d1 *= 2;
    
        for($i = 0; $i < 9; $i++) {
            $d1 += charAt($c, $i)*(11-$i);
        }
    
        $d1 = 11 - ($d1 % 11);
    
        if($d1 > 9) $d1 = 0;
        if(charAt($dv, 1) != $d1) {
            return false;
        }
        
        return true;
    }

    function validateCNPJ($value_param) {
        $c  = $value_param.substr(0,12);
        $dv = $value_param.substr(12,2);
        $d1 = 0;
    
        for($i = 0; $i < 12; $i++) {
            $d1 += $c.charAt(11, $i)*(2+($i % 8));
        }
    
        if($d1 == 0) return false;
    
        $d1 = 11 - ($d1 % 11);
    
        if($d1 > 9) $d1 = 0;
    
        if($dv.charAt($dv, 0) != $d1) {
            return false;
        }
    
        $d1 *= 2;
    
        for($i = 0; $i < 12; $i++) {
            $d1 += $c.charAt(11, $i)*(2+(($i+1) % 8));
        }
    
        $d1 = 11 - ($d1 % 11);
    
        if($d1 > 9) $d1 = 0;
        if($dv.charAt($dv, 1) != $d1) {
            return false;
        }
    
        return true;
    }

    function charAt($str, $pos) {
        return $str[$pos];
    }

    /****************************************************************************
    * Função para o retorno da tela
    ******************************************************************************/

    function returnMessage($method, $errors = false) {
        if($errors) {
            http_response_code(500);
            echo json_encode($errors);
        } else {
            http_response_code(200);
            
            switch ($method) {
                case "POST":
                    echo "Adicionado com sucesso.";
                break;
                case "PUT":
                    echo "Alterado com sucesso.";
                break;
                case "DELETE":
                    echo "Deletado com sucesso.";
                break;
                default:
                    echo "Operação realizada com sucesso.";
                break;
            }
        }
    }

    /****************************************************************************
    * Função para requisição das APIs
    ******************************************************************************/

    function requestGET($url_header) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url_header,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => false,
            CURLOPT_HTTPHEADER     => [ 
                "Content-Type: application/json",
                "Content-Length: 0"
            ]
        ]);

        $response = curl_exec($curl); 
        curl_close($curl);

        return $response;
    }

    function requestPOST($url_header, $data_param) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url_header,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data_param),
            CURLOPT_HTTPHEADER     => [ 
                "Content-Type: application/json",
                "Content-Length: ".strlen(json_encode($data_param)),
            ]
        ]);

        $response = curl_exec($curl); 
        curl_close($curl);

        return $response;
    }

    /****************************************************************************
    * Funções de Manipulação de permissões e atividades
    ******************************************************************************/
    
    function loadPermissions($user = false) {
        global $GenericService;

        if(!$user) {
            $user = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : false;
        }

        unset($_SESSION["pode_acessar"]);

        $GenericService->getPermissions($user);
    }

    function validatePermissionsIcon($setting, $agent_id = false) {
        $return = false;

        if(validateAccess(strtoupper($setting), false, false, $agent_id)) {
            $return = true;
        }

        return $return;
    }

    function validateAccess($code_activitie = false, $function = false, $user = false, $id_agent = false) {
        if(!isset($_SESSION["pode_acessar"])) {
            loadPermissions();
        }

        $what       = !$code_activitie ? getURL() : $code_activitie;
        $has_access = isset($_SESSION["pode_acessar"][$what]) ? $_SESSION["pode_acessar"][$what] : false;

        if($id_agent) {
            if($function) {
                $return = ($has_access[$id_agent][$function] == "S");
            } else {
                $return = isset($has_access[$id_agent]);
            }
        } else {
            if($function) {
                if(!$has_access) {
                    $return = true;
                } else {
                    foreach($has_access as $arr) {
                        if($arr[$function] == "S") {
                            $return = true;
                            break;
                        } else {
                            $return = false;
                        }
                    }
                }
            } else {
                $return = $has_access;
            }
        }

        return $return;
    }

    function getAge($bithdayDate) {
        $date = new DateTime($bithdayDate);
        $now  = new DateTime();

        $interval = $now->diff($date);
        return $interval->y;
    }

    function getNumberWeek($dt_sorteio) {
        return date("w", strtotime($dt_sorteio));
    }

    /****************************************************************************
    * Funções de manipulação da tela de Dashboard e Comparativo de Agenciadores
    ******************************************************************************/

    function getDataToRenderGraphics($data_request, $field, $dateLabel = false) {
        $list_open = $list_paid = $list_cancel = $list_expired = $infos = [];
    
        if($data_request["open"]) {
            foreach($data_request["open"] as $row) {
                $infos[$row[$field]] = [0, 0, 0, 0];
                $infos[$row[$field]][0] = $row["Itens"];
            }    
        }

        if($data_request["paid"]) {
            foreach($data_request["paid"] as $row) {
                if(!isset($infos[$row[$field]])) {
                    $infos[$row[$field]] = [0, 0, 0, 0];
                }

                $infos[$row[$field]][1] = $row["Itens"];
            }
        }

        if($data_request["cancel"]) {
            foreach($data_request["cancel"] as $row) {
                if(!isset($infos[$row[$field]])) {
                    $infos[$row[$field]] = [0, 0, 0, 0];
                }

                $infos[$row[$field]][2] = $row["Itens"];
            }
        }

        if($data_request["expired"]) {
            foreach($data_request["expired"] as $row) {
                if(!isset($infos[$row[$field]])) {
                    $infos[$row[$field]] = [0, 0, 0, 0];
                }

                $infos[$row[$field]][3] = $row["Itens"];
            }
        }

        $labels = array_keys($infos);
        sort($labels);

        foreach($labels as $indice) {
            array_push($list_open, $infos[$indice][0]);
            array_push($list_paid, $infos[$indice][1]);
            array_push($list_cancel, $infos[$indice][2]);
            array_push($list_expired, $infos[$indice][3]);
        }

        if($dateLabel) {
            $return["labels"] = array_map("dateLabel", $labels);
        } else {
            $list_labels = array();

            foreach($labels as $value) {
                array_push($list_labels, $value."h");
            }

            $return["labels"] = $list_labels;
        }

        return array(
            "list_open"    => $list_open,
            "list_paid"    => $list_paid,
            "list_cancel"  => $list_cancel,
            "list_expired" => $list_expired,
            "return"       => $return
        );
    }

    function getDataToRenderTotals($data_request) {
        $total = 0;

        $data_all_carts = $data_request["all_carts"];
       
        $return[0]["carts"] = 0;
        $return[0]["itens"] = 0;

        $return[1]["carts"] = 0;
        $return[1]["itens"] = 0;

        $return[2]["carts"] = 0;
        $return[2]["itens"] = 0;

        $return[3]["carts"] = 0;
        $return[3]["itens"] = 0;

        if($data_all_carts) {
            foreach($data_all_carts as $row) {
                if($row["situacao"] == 1) {//Aberto
                    $return[0]["carts"] = $row["Total"];
                    $return[0]["itens"] = $row["Itens"] ? $row["Itens"] : 0;
                    $total += $row["Itens"];
                } else if($row["situacao"] == 2) {//Pago
                    $return[1]["carts"] = $row["Total"];
                    $return[1]["itens"] = $row["quantidadeTotal"];
                    $total += $row["quantidadeTotal"];
                } else if($row["situacao"] == 3) {//Cancelado
                    $return[2]["carts"] = $row["Total"];
                    $return[2]["itens"] = $row["Itens"] ? $row["Itens"] : 0;
                    $total += $row["Itens"];
                }  else if($row["situacao"] == 4) {//Expirado
                    $return[3]["carts"] = $row["Total"];
                    $return[3]["itens"] = $row["Itens"] ? $row["Itens"] : 0;
                    $total += $row["Itens"];
                }
            }
        }

        return array(
            "data"  => $return,
            "total" => $total
        );
    }

    /****************************************************************************
    * Funções de Manipulações genéricas
    ******************************************************************************/

    function getColorHex() {
        return array ( 
            0 => '#7458C6', 1 => '#E8F31C', 2 => '#68B35B', 3 => '#F78C0D', 4 => '#C5BE32', 5 => '#0BD4AD', 
            6 => '#F44EB1', 7 => '#4B2508', 8 => '#CE6352', 9 => '#091D02', 10 => '#20D5B4', 11 => '#8F76AE', 
            12 => '#358A08', 13 => '#37DE03', 14 => '#711683', 15 => '#CC05A1', 16 => '#EC36BE', 17 => '#FF313C', 
            18 => '#0DC1CA', 19 => '#741D21', 20 => '#42E115', 21 => '#7E531C', 22 => '#2EBF78', 23 => '#6D983E', 
            24 => '#B399FA', 25 => '#9D06B0', 26 => '#BBF9C9', 27 => '#65C723', 28 => '#D8B4FC', 29 => '#92C3AB', 
            30 => '#B836ED', 31 => '#F42494', 32 => '#F8C9DE', 33 => '#6FFE2A', 34 => '#877D1F', 35 => '#FF584D', 
            36 => '#116D00', 37 => '#70E66A', 38 => '#FEBA76', 39 => '#4A3236', 40 => '#A4E32B', 41 => '#46F1F8', 
            42 => '#A4A33F', 43 => '#B58972', 44 => '#02646C', 45 => '#B0A473', 46 => '#915B74', 47 => '#6C92C2', 
            48 => '#E16613', 49 => '#193236', 50 => '#0D8FB2', 51 => '#9E6934', 52 => '#4705FF', 53 => '#AE51CA', 
            54 => '#9963A3', 55 => '#9A26AA', 56 => '#B9397A', 57 => '#CCE166', 58 => '#B2DC70', 59 => '#E94021', 
            60 => '#277061', 61 => '#0A5A83', 62 => '#EFFA17', 63 => '#B24FE5', 64 => '#9B2376', 65 => '#270961', 
            66 => '#896716', 67 => '#8BDAAA', 68 => '#3DA892', 69 => '#21CAE0', 70 => '#878D5A', 71 => '#B2A364', 
            72 => '#A54FC7', 73 => '#F02F83', 74 => '#ABE58E', 75 => '#9CC62B', 76 => '#C9426E', 77 => '#26D611', 
            78 => '#96250C', 79 => '#96EDA7', 80 => '#E495E2', 81 => '#EF5A19', 82 => '#918F7B', 83 => '#7542A8', 
            84 => '#A8433B', 85 => '#103686', 86 => '#E681DA', 87 => '#59AB99', 88 => '#6A771F', 89 => '#F6AE5B', 
            90 => '#18E27D', 91 => '#DAD647', 92 => '#2F2EEC', 93 => '#9F088E', 94 => '#42CFCB', 95 => '#E2D261', 
            96 => '#4CCE62', 97 => '#60EC51', 98 => '#755FB3', 99 => '#6C8813', 100 => '#451802', 101 => '#5BCF13', 
            102 => '#515063', 103 => '#066066', 104 => '#0B6639', 105 => '#F6A376', 106 => '#FE5062', 107 => '#D0F465', 
            108 => '#5ED425', 109 => '#F92DAB', 110 => '#4A6236', 111 => '#845740', 112 => '#61A4DA', 113 => '#3C5211', 
            114 => '#C0968E', 115 => '#2209A6', 116 => '#66409C', 117 => '#F57A1C', 118 => '#A3DC94', 119 => '#9DB754'
        );
    }

    function generateRandomColorHex() {
        $array_color     = array();
        $color_range_hex = array("0","A","1","B","2","C","3","D","4","E","5","F","6","7","8","9");

        for($y = 0; $y < 120; $y++) {
            $color = "";

            for($x = 0; $x < 6; $x++) {
                $color .= $color_range_hex[array_rand($color_range_hex, 1)];
            }

            array_push($array_color, "#".$color);
        }

        return $array_color;
    }

    function dateLabel($a) {
        return date_format(date_create($a), "d/m");
    }

    /****************************************************************************
    * Funções de manipulação de diretórios e arquivos
    ******************************************************************************/

    function createFolderIfDontExist($id_edition, $base = "users") {
        $path = "uploads/$base/$id_edition";

        if(!file_exists($path)) mkdir($path, 0755, true);
    }

    function uploadFile($file, $dir, $new_name = false){
        $path = $file['name'];
        $ext  = pathinfo($path, PATHINFO_EXTENSION);
        $name = !$new_name ? $file["name"] : "$new_name.$ext";

        $destination = "uploads/$dir/$name";
        $result_name = $name;

        if(move_uploaded_file($file["tmp_name"], $destination)) {
            return $destination;
        } else {
            return false;
        }
    }

    function filesFolder($path) {
        if(!file_exists($path)) {
            return [];
        }

        $fi = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
        $total_files = iterator_count($fi);

        if($total_files == 0) {
            return [];
        } else {
            if($handle = opendir($path)) {
                while(false !== ($entry = readdir($handle))) {
                if($entry != "." && $entry != "..")
                    $return[] = array("name" => $entry, "size" => fileSizeConvert(filesize("$path/$entry"), false), "link" => "$path/$entry");
                }

                closedir($handle);
            }

            return $return;
        }
    }

    function countFilesFolder($path) {
        if(!file_exists($path))
        return 0;

        $fi = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
        return iterator_count($fi);
    }

    function proccessImageUser($file) {
        $id_user = $_SESSION["user"]["id"];

        $directory_update = "../../uploads/users/";

        if(!is_dir($directory_update)) {
            mkdir($directory_update, 0777, true);
        }

        $dir_user_session = $_SESSION["user"]["avatar"] ? "../../".$_SESSION["user"]["avatar"] : false;

        if($dir_user_session and file_exists($dir_user_session)) {
            unlink($dir_user_session);
        }

        $validation = explode(".", $file["name"]);

        $path = "$directory_update$id_user.".end($validation);

        $_SESSION["user"]["avatar"] = $path;

        if(move_uploaded_file($file["tmp_name"], $path)) {
            createSquareImage($path, $path, 200);
        } else {
            exit("erro ao enviar arquivo: ".$file["error"]);
        }
    }

    function getPeriod($hora) {
        $timestamp = strtotime($hora);
        $hora = (int)date("H", $timestamp);
    
        if($hora >= 5 && $hora < 12) {
            return "0";
        } else if($hora >= 12 && $hora < 18) {
            return "1";
        } else if($hora >= 18 && $hora < 24) {
            return "2";
        } else {
            return "4";
        }
    }

    /****************************************************************************
    * Funções de manipulação do Login
    ******************************************************************************/

    function proccessError($data_post) {
        $array_errors = array();

        if(!isset($data_post["idAgenciador"])) { $array_errors[] = ["message" => "Selecione o Produto", "field" => "idAgenciador"]; }
        if(!isset($data_post["user"])) { $array_errors[] = ["message" => "Preencha o campo Usuário", "field" => "user"]; }
        if(!isset($data_post["pass"])) { $array_errors[] = ["message" => "Preencha o campo Senha", "field" => "pass"]; }

        return $array_errors;
    }
    
    function proccessLogin($data_post) {
        global $GenericService;

        $array_errors = proccessError($data_post);

        sort($array_errors);

        if($array_errors) {
            return ["message" => "Falha na solicitação!", "error" => true, "statusCode" => 400, "fileds" => $array_errors];
        }
      
        $sql_data_user = $GenericService->getUser($data_post);
        
        if($sql_data_user["query"]) {
            $sql_data_permission = $GenericService->getPermission($sql_data_user["query"]["id"], $data_post["idAgenciador"]);

            if($sql_data_permission == true) {
                if(Bcrypt::check($sql_data_user["pass"], $sql_data_user["query"]["salt"])) {

                    unset($_COOKIE["agenciador"], $_COOKIE["usuario"], $_COOKIE["lembrar"], $_SESSION["pode_acessar"]);
                    
                    $_SESSION["id_agent"] = $data_post["idAgenciador"];

                    $sql_data_user["query"]["hash"] = null;
                    $_SESSION["logged"]             = true;
                    $_SESSION["user"]               = $sql_data_user["query"];
                    $_SESSION["user"]["avatar"]     = false;
                    
                    $GenericService->getAgent($data_post["idAgenciador"]);

                    $files_check = ["png", "jpg"];
                    $_SESSION["nomeUsuario"] = $sql_data_user["query"]["nome"];

                    $id_user = $sql_data_user["query"]["id"];

                    foreach($files_check as $ext) {
                        if(file_exists("uploads/users/$id_user.$ext")) {
                            $_SESSION["user"]["avatar"] = "uploads/users/$id_user.$ext";
                        }
                    }

                    if(isset($data_post["lembrar"])) {
                        setcookie("agenciador", $data_post["idAgenciador"], strtotime("+1 year"), "/");
                        setcookie("usuario", $sql_data_user["query"]["codigo"], strtotime("+1 year"), "/");
                        setcookie("lembrar", "true", strtotime("+1 year"), "/");
                    } else {
                        setcookie("lembrar", "false", strtotime("+1 year"), "/");
                    }

                    return ["method" => "login", "url" => "index", "error" => false, "statusCode" => 200];

                } else {
                    return ["message" => "Usuário ou senha incorretos. Por favor, tente novamente!", "error" => true, "statusCode" => 400];
                } 
            } else {
                return ["message" => "Não autorizado Usuário / Produto!", "error" => true, "statusCode" => 400];
            }
        } else {
            return ["message" => "Usuário ou senha incorretos. Por favor, tente novamente!", "error" => true, "statusCode" => 400];
        }     
    }

    /****************************************************************************
    * Funções de manipulação de gráficos Comparativo de Agenciadores e Index
    ******************************************************************************/

    function getConfgsGraphAgentsCompDay($ids_formatated) {
        global $data_db;
        global $GenericService;

        $_GRAPH = array(); 

        $sql_data_agents = $GenericService->getAgentsByIds($ids_formatated, true);

        while($row = $data_db->getLine($sql_data_agents)) {
            $_GRAPH[$row["id"]] = array(
                "agenciador" => array(
                    "id"   => $row["id"], 
                    "nome" => $row["nome"], 
                    "cor"  => $GenericService->getParameterContent("COLOR_BACKGROUND", $row["id"])
                )
            );
        }

        return $_GRAPH;
    }

    /****************************************************************************
    * Funções de manipulação de gráficos Comparativo de Edições
    ******************************************************************************/

    function getConfgsGraphEditionsCompDay($all_edition) {
        $day_week = [
            "0"  => ["d_week" => "Domingo", "a_week", "DOM"],
            "1"  => ["d_week" => "Segunda", "a_week", "SEG"],
            "2"  => ["d_week" => "Terça", "a_week", "TER"],
            "3"  => ["d_week" => "Quarta", "a_week", "QUA"],
            "4"  => ["d_week" => "Quinta", "a_week", "QUI"],
            "5"  => ["d_week" => "Sexta", "a_week", "SEX"],
            "6"  => ["d_week" => "Sábado", "a_week", "SAB"],
        ];
        
        $arr_labels = $_GRAPH = array();

        for($i = 0; $i < 7; $i++) {
            array_push($arr_labels, $day_week[$i]["d_week"]);
        }

        $_COLOR = array(0 => "#879aad", 1 => "#6c757d", 2 => "#7aab7d");

        for($x = 0; $x < 3; $x++) {
            $label = "Base - ";
    
            $label = $x == 0 ? "C1 - " : $label;
            $label = $x == 1 ? "C2 - " : $label;
    
            $_GRAPH[$all_edition[$x]["id"]] = array(
                "edicao" => array(
                    "id"         => $all_edition[$x]["id"], 
                    "nome"       => $label.$all_edition[$x]["numeroEdicao"], 
                    "dt_sorteio" => $all_edition[$x]["dataHoraSorteio"], 
                    "cor"        => $_COLOR[$x]
                )
            );
        }

        return [
            "arr_labels" => $arr_labels,
            "graph" => $_GRAPH
        ];
    }
    
    /****************************************************************************
    * Funções de manipulação de Imagem
    ******************************************************************************/

    function createSquareImage($original_file, $destination_file = NULL, $square_size = 96) {
		
		if(isset($destination_file) and $destination_file != NULL) {
			if(!is_writable($destination_file)) {
                echo '<p style="color:#FF0000">A pasta de destino não pode ser escrita.</p>'; 
            }
        }
		
		$imagedata       = getimagesize($original_file);
		$original_width  = $imagedata[0];	
		$original_height = $imagedata[1];
		
		if($original_width > $original_height) {
			$new_height = $square_size;
			$new_width = $new_height * ($original_width / $original_height);
		}

		if($original_height > $original_width) {
			$new_width = $square_size;
			$new_height = $new_width * ($original_height / $original_width);
		}

		if($original_height == $original_width) {
            $new_width = $new_height = $square_size;
        }
		
		$new_width  = round($new_width);
		$new_height = round($new_height);
		
		// load the image
		if(substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")) {
            $original_image = imagecreatefromjpeg($original_file);
        }
			
		if(substr_count(strtolower($original_file), ".gif")) {
            $original_image = imagecreatefromgif($original_file);
        }
			
		if(substr_count(strtolower($original_file), ".png")) {
            $original_image = imagecreatefrompng($original_file);
        }
			
		$smaller_image = imagecreatetruecolor($new_width, $new_height);
		$square_image  = imagecreatetruecolor($square_size, $square_size);
		
		imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
		
		if($new_width > $new_height) {
			$difference = $new_width-$new_height;
			$half_difference =  round($difference/2);
			imagecopyresampled($square_image, $smaller_image, 0-$half_difference+1, 0, 0, 0, $square_size+$difference, $square_size, $new_width, $new_height);
		}

		if($new_height > $new_width) {
			$difference = $new_height-$new_width;
			$half_difference =  round($difference/2);
			imagecopyresampled($square_image, $smaller_image, 0, 0-$half_difference+1, 0, 0, $square_size, $square_size+$difference, $new_width, $new_height);
		}

		if($new_height == $new_width) {
            imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
        }
		
		// if no destination file was given then display a png		
		if(!$destination_file) {
            imagepng($square_image, NULL, 9);
        }
		
		// save the smaller image FILE if destination file given
		if(substr_count(strtolower($destination_file), ".jpg")) {
            imagejpeg($square_image, $destination_file, 100);
        }

		if(substr_count(strtolower($destination_file),  ".gif")) {
            imagegif($square_image, $destination_file);
        }	

		if(substr_count(strtolower($destination_file), ".png")) {
            imagepng($square_image, $destination_file, 9);
        }

		imagedestroy($original_image);
		imagedestroy($smaller_image);
		imagedestroy($square_image);
    }

    function getAllAgentsToSelect($edit) {
        global $data_db;
        global $GenericService;
        
        $id_actividade = $_SESSION["pagina_atual"][$_SESSION["id_agent"]]["idAtividade"];
        
        if($edit) {
            $data_db->listCombo("agenciador", "id", "nome", $edit["idAgenciador"], "ORDER BY nome");
        } else {
            if(!$id_actividade) {    
                foreach($_SESSION["pagina_atual"] as $permissao) {
                    $id_actividade = $_SESSION["pagina_atual"][$permissao["idAgenciador"]]["idAtividade"];
            
                    if($id_actividade) {
                        $GenericService->listComboAgent($id_actividade, $permissao["idAgenciador"]);
                        break;
                    }
                }
            } else {
                $GenericService->listComboAgent($id_actividade, $_SESSION["id_agent"]);
            }
        }
    }
?>