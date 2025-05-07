<?php
    function maskGeneric($mask, $str) {

        $str = str_replace(" ", "", $str);
    
        for($i=0; $i < strlen($str); $i++) {
            $mask[strpos($mask,"#")] = $str[$i];
        }
    
        return $mask;
    }

    function separeNumber($text) {
        $result = "";

        for($x = 0; $x < strlen($text); $x++) {
            if((ord($text[$x]) >= 48) && (ord($text[$x]) <= 57)) {
                $result .= $text[$x];
            }
        }
        
        return $result;
    }

    function formatValue($value, $decimal_places) {
        return number_format($value, $decimal_places, ',', '.');
    }

    function removeCaractersValue($value) {
        $value = str_replace('.', '', $value);
        return str_replace(',', '.', $value);
    }

    function nameLGPD($name) {
        if((trim($name) == "")||($name == NULL)) {
            return NULL;
        }
    
        $name         = explode(' ', $name);
        $name_correct = [];
        $nr_names     = count($name);

        for($i =0; $i < $nr_names; $i++) {
            if(trim($name[$i]) != "") {
                array_push($name_correct, $name[$i]);
            }
        }
    
        $final_name = $name_correct[0];
        $nr_names   = count($name_correct);

        if($nr_names > 2) {
            $final_name .= " ".$name_correct[1];

            for($i = 2; $nr_names > $i; $i++) {
                $dummy_name = substr($name_correct[$i], 0, 1);
                $dummy_name = $dummy_name."*";
                $final_name.= " ".$dummy_name;
            }
        } else {
            $dummy_name = substr($name_correct[1], 0, 1);
            $dummy_name = $dummy_name."*";
            $final_name.= " ".$dummy_name;
        }
    
        return $final_name;
    }

    function fileSizeConvert($bytes, $showExtension = true) {
        $bytes = floatval($bytes);

        $ar_bytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            )
        );

        foreach($ar_bytes as $ar_item) {
            if($bytes >= $ar_item["VALUE"]) {
                $result = $bytes / $ar_item["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2)));

                if($showExtension)
                    $result .= " " .$ar_item["UNIT"];
                break;
            }
        }

        return $result;
    }

    function fieldNumber($value, $decimal_places = 0) {
        return array(
            "display" => formatValue($value, $decimal_places),
            "num-val" => $value
        );
    }

    function isCPF($number) {
        return (strlen($number) == 14);
    }

    function isCNPJ($number) {
        return (strlen($number) == 18);
    }

    function onlyNumber($value) {
        return preg_replace("/[^0-9]/", "", $value);
    }
?>