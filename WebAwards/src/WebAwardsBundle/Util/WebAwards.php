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
}