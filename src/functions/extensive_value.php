<?php
    class Extense {
        public static function removeFormatNumber($str_number) {
            $str_number = trim( str_replace( "R$", null, $str_number ) );
            $vet_comma = explode( ",", $str_number );

            if( count( $vet_comma ) == 1 ) {
                $accent = array(".");
                $result = str_replace( $accent, "", $str_number );
                
                return $result;
            } else if( count( $vet_comma ) != 2 ) {
                return $str_number;
            }

            $str_number = $vet_comma[0];
            $str_decimal = mb_substr( $vet_comma[1], 0, 2 );

            $accent = array(".");
            $result = str_replace( $accent, "", $str_number );
            $result = $result . "." . $str_decimal;

            return $result;
        }

        public static function converte( $value = 0, $bol_display_currency = true, $bol_feminine_word = false ) {

            $value = self::removeFormatNumber( $value );

            $single = null;
            $plural = null;

            if( $bol_display_currency ) {
                $single = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
                $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
            } else {
                $single = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
                $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
            }

            $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
            $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
            $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
            $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

            if( $bol_feminine_word ) {
                if($value == 1) {
                    $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
                } else {
                    $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
                }
                
                $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
            }

            $z = 0;

            $value  = number_format( $value, 2, ".", "." );
            $entire = explode( ".", $value );

            for( $i = 0; $i < count( $entire ); $i++ )
                for( $ii = mb_strlen( $entire[$i] ); $ii < 3; $ii++ )
                    $entire[$i] = "0" . $entire[$i];

            // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
            $rt = null;
            $fim = count( $entire ) - ($entire[count( $entire ) - 1] > 0 ? 1 : 2);

            for( $i = 0; $i < count( $entire ); $i++ ) {
                $value = $entire[$i];
                $rc = (($value > 100) && ($value < 200)) ? "cento" : $c[$value[0]];
                $rd = ($value[1] < 2) ? "" : $d[$value[1]];
                $ru = ($value > 0) ? (($value[1] == 1) ? $d10[$value[2]] : $u[$value[2]]) : "";
                $r  = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
                $t  = count( $entire ) - 1 - $i;
                $r .= $r ? " " . ($value > 1 ? $plural[$t] : $single[$t]) : "";

                if( $value == "000") {
                    $z++;
                } else if( $z > 0 ) {
                    $z--;
                }

                if( ($t == 1) && ($z > 0) && ($entire[0] > 0) ) {
                    $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
                }

                if( $r ) {
                    $rt = $rt . ((($i > 0) && ($i <= $fim) && ($entire[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
                }
            }

            $rt = mb_substr( $rt, 1 );

            return($rt ? trim( $rt ) : "zero");
        }

        public static function nameMonth($number_month) {
            switch ($number_month) {
                case 1: return "janeiro";
                case 2: return "fevereiro";
                case 3: return "março";
                case 4: return "abril";
                case 5: return "maio";
                case 6: return "junho";
                case 7: return "julho";
                case 8: return "agosto";
                case 9: return "setembro";
                case 10: return "outubro";
                case 11: return "novembro";
                case 12: return "dezembro";
            }
        }
    }
?>