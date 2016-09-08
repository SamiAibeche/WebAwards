<?php

namespace WebAwardsBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;
/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Our new getAllPosts() method
     *
     * 1. Create & pass query to paginate method
     * 2. Paginate will return a `\Doctrine\ORM\Tools\Pagination\Paginator` object
     * 3. Return that object to the controller
     *
     * @param integer $currentPage The current page (passed from controller)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */

    public function getAllPosts($currentPage)
    {

        $query = $this->createQueryBuilder('p')
            ->where('p.isVisible = 1')
            ->orderBy('p.dateAdd', 'DESC')
            ->getQuery();

        //No need to manually get get the result ($query->getResult())

        $paginator = $this->paginate($query, $currentPage, 6);


        return $paginator;
    }



    public function getAllPostsFrom($currentPage, $from)
    {

        if($from=="agency" || $from=="freelance" ){
            $query = $this->createQueryBuilder('p')
                ->join('p.idAuthor', 'u')
                ->where('p.isVisible = 1')
                ->andWhere("u.role = '".$from."'")
                ->orderBy('p.dateAdd', 'DESC')
                ->getQuery();

        } else if($from == "junior"){

            //Get All Users from DB
            $users = $this->getEntityManager()->getRepository('WebAwardsBundle:User')->findAll();

            //Today
            $now = time();

            $tabUserId = [];

            //For all user
            foreach($users as $user){
                //Get Birthday
                $birthdayUser = $user->getBirthdayAt()->format("Y-m-d");
                $time = strtotime($birthdayUser);

                //Diff between 2 dates
                $diff = abs($now - $time);

                //Set the number of year
                $nbYear = (int) ($diff / (365*60*60*24));

                //Recup the id of the younger users
                if($nbYear >= 18 && $nbYear <=25) {
                    $tabUserId[] = $user->getId();
                }
            }
            //Get Project of the user
            $query = $this->createQueryBuilder('p');

               $query
                    ->where('p.isVisible = 1')
                    ->andWhere('p.idAuthor IN (:idUser)')
                    ->setParameter('idUser', $tabUserId)
                    ->orderBy('p.dateAdd', 'DESC')
                    ->getQuery();

        } else if($form = "honorable"){
            
            $query = $this->createQueryBuilder('p')
                ->join('p.votes', 'v')
                ->where('p.isVisible = 1')
                ->andWhere("v.nbTotal > 7")
                ->orderBy('p.dateAdd', 'DESC')
                ->getQuery();
        }


        //No need to manually get get the result ($query->getResult())
        $paginator = $this->paginate($query, $currentPage, 9);
        return $paginator;
    }

    public function paginate($dql, $page = 1, $limit)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}
