<?php
    /****************************************************************************
    * Funções de Manipulação e Formatação de Data e Hora
    ******************************************************************************/
    
    //Deve ser recebido sempre no formato 2021-12-09
    function dateSimpleBR($date) {
        return date_format(new DateTime($date), 'd/m/y');
    }

    function getDateUS($date) {
        return date_format(new DateTime($date), 'Y-m-d');
    }

    function dateCompleteBR($date) {
        if(empty($date)) return "";

        $date = separeNumber($date);

        if(strlen($date) >= 8) {
            return sprintf("%2s/%2s/%4s", substr($date, 6, 2), substr($date, 4, 2), substr($date, 0, 4));
        } else {
            return sprintf("%2s/%2s/%4s", substr($date, 4, 2), substr($date, 2, 2), "20".substr($date, 0, 2));
        }
    }

    //Deve ser recebido sempre no formato 2021-12-09 15:00:00 ou 09/12/2021 15:00:30
    function hourComplete($date) {
        $date = separeNumber($date);

        if(strlen($date) >= 14) {
            return sprintf("%2s:%2s", substr($date, 8, 2), substr($date, 10, 2));
        } else {
            return sprintf("%2s:%2s", substr($date, 8, 2), substr($date, 10, 2));
        }
    }

    //Deve ser recebido sempre no formato 2021-12-09 15:00
    function dateHourBR($date) {
        $date = separeNumber($date);

        if(strlen($date) >= 14) {
            return sprintf("%2s/%2s/%4s %2s:%2s", substr($date, 6, 2), substr($date, 4, 2), substr($date, 0, 4), substr($date, 8, 2), substr($date, 10, 2));
        } else {
            return sprintf("%2s/%2s/%4s %2s:%2s", substr($date, 4, 2), substr($date, 2, 2), "20".substr($date, 0, 2), substr($date, 8, 2), substr($date, 10, 2));
        }
    }

    //Deve ser recebido sempre no formato 09/12/2021
    function dateUS($date) {
        $date = separeNumber($date);

        if(strlen($date) >= 8) {
            return sprintf("%4s-%2s-%2s", substr($date,4,4), substr($date, 2, 2), substr($date, 0, 2));
        } else {
            return sprintf("%4s-%2s-%2s", "20".substr($date, 4, 2), substr($date, 2, 2), substr($date, 0, 2));
        }
    }
?>