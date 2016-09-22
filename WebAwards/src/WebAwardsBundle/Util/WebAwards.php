<?php

namespace WebAwardsBundle\Util;



class WebAwards {


    public static function checkVote($vote) {

        //Vérifie la validité du nombre entré

        //Champs vide
        if(empty($vote)){
            return "empty";
        }

        //Champs avec uniquement des espaces
        if(empty(trim($vote))){
            return "empty";
        }

        //Champs contenant des espaces
        $pos = strrpos($vote," ");
        if($pos){
            return "space";
        }

        //Champs non numérique
        if(!is_numeric($vote)){
            return false;
        }

        //Arrondi du nombre à un chiffre après la virgule.
        $vote = round($vote, 1, PHP_ROUND_HALF_DOWN);

        //Champs inférieur à 0
        if($vote < 0){
            return "négatif";
        }

        //Champs supérieur à 10
        if($vote > 10){
            return "big";
        }

        //Champs OK
        return true;
    }

    public static function checkLastMonthWinner($arrData){

        //Champs vide
        if(empty($arrData)){
            return "empty";
        }
        //Champs invalide, Array attendu
        if(!(is_array($arrData))){
             return "invalidArray";
        }
        //Taille du tableau invalid
        if(count($arrData) !== 2){
            return "invalid";
        }
        //Clé inexistante
        if(!(array_key_exists('month', $arrData))){
            return "invalidMonthKey";
        }
        //Seconde clé inexistante
        if(!(array_key_exists('year', $arrData))){
            return "invalidYearKey";
        }

        $currMonth = $arrData["month"];
        $currYear = $arrData["year"];

        //Données non numérique
        if(!(is_numeric($currMonth)) || !(is_numeric($currYear))){
            return "invalidData";
        }

        $currMonth = (int) $arrData["month"];
        $currYear  = (int) $arrData["year"];

        //Mois non compris entre 1 et 12
        if( ($currMonth>12) || ($currMonth<1) ){
            return "invalidMonth";
        }

        $month = (int) date("m");
        $year = (int) date("Y");

        //Mois de jancier de cette année
        if($currMonth == 1 && $currYear == $year){
            return "dateOk";
        }
        //Mois de décembre de l'année passée
        if($currMonth == 1 && $currYear == ($year - 1)){
            return "dateLastOk";
        }

        //Année passée
        if( ($currYear<2016) ) {
            return "invalidYear";
        }
        //Mois je janvier
        if( $currMonth == 1){
            return "janvier";
        }
        //Données valide
        if( ($currMonth <= 12) || ($currMonth >=1 ) && $currYear == $year){
            return true;
        }
        
    }
}