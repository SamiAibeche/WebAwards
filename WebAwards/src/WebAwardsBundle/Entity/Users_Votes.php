<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users_Votes
 *
 * @ORM\Table(name="users__votes")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\Users_VotesRepository")
 */
class Users_Votes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer")
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_vote", type="integer")
     */
    private $idVote;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Users_Votes
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idVote
     *
     * @param integer $idVote
     *
     * @return Users_Votes
     */
    public function setIdVote($idVote)
    {
        $this->idVote = $idVote;

        return $this;
    }

    /**
     * Get idVote
     *
     * @return int
     */
    public function getIdVote()
    {
        return $this->idVote;
    }
}

