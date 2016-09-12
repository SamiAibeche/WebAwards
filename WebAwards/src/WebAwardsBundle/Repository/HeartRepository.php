<?php

namespace WebAwardsBundle\Repository;

/**
 * HeartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HeartRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     *
     * Vérifie si l'utilisateur a déjà voté pour le projet
     * @param $idProject Id du projet
     * @param $currentUser Id de l'utilisateur
     * @return bool
     */
    public function verifyHasLike($idProject, $currentUser){


        if(is_array($currentUser)){
            $idUser = $currentUser[0]->getId();
        } else {
            $idUser = $currentUser->getId();
        }

        //Selectionne le like, dont l'idProject & l'idUser correspondent
        $query = $this->createQueryBuilder('h')
            ->where('h.idUser = '.$idUser.'')
            ->andWhere('h.idProject = '.$idProject.'')
            ->getQuery();


        $result =  $query->getResult();
        
        if(empty($result)){ //Si aucun like n'a été trouvé
            return true;
        } else {    //sinon
            return false;
        }
    }

    /**
     *
     * Renvoi un tableau d'objet Hearts
     * @param $idProject
     * @param $currentUser
     * @return mixed
     */
    public function getHeart($idProject, $currentUser){

        $idUser = $currentUser->getId();
        $query = $this->createQueryBuilder('h')
            ->where('h.idUser = '.$idUser.'')
            ->andWhere('h.idProject = '.$idProject.'')
            ->getQuery();

        $result =  $query->getResult();

        return $result[0];

    }

    /**
     *
     * Recupère le nombre de like pour le projet dans la DB
     *
     * @param $idProject
     * @return int Le nombre de like
     */
    public function getNbHeart($idProject){


        $query = $this->createQueryBuilder('h')
            ->where('h.idProject = '.$idProject.'')
            ->getQuery();

        $result =  $query->getResult();
        $nbHeart = count($result);

        return $nbHeart;

    }
}
