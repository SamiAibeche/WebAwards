<?php

namespace WebAwardsBundle\Repository;

/**
 * VoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VoteRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     *
     * Verify if the user has been voted for the current project
     * 
     * @param $idUser  User id
     * @param $idProject  Project id
     * @return bool
     */
    public function VerifyUserVote($idUser, $idProject)
    {

        $query = $this->createQueryBuilder('v')
            ->where('v.idUser = '.$idUser.'')
            ->andWhere('v.idProject = '.$idProject.'')
            ->getQuery();

       $result =  $query->getResult();

        if(empty($result)){
            return true;
        } else {
            return false;
        }

    }
}
