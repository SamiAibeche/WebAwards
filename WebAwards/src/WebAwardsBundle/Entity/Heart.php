<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Heart
 *
 * @ORM\Table(name="heart")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\HeartRepository")
 */
class Heart
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
     * @var bool
     *
     * @ORM\Column(name="isLike", type="boolean")
     */
    private $isLike;


    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="hearts")
     * @ORM\JoinColumn(name="idProject", referencedColumnName="id")
     */
    private $idProject;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="hearts")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     */
    private $idUser;


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
     * Set isLike
     *
     * @param boolean $isLike
     *
     * @return Heart
     */
    public function setIsLike($isLike)
    {
        $this->isLike = $isLike;

        return $this;
    }

    /**
     * Get isLike
     *
     * @return bool
     */
    public function getIsLike()
    {
        return $this->isLike;
    }

    /**
     * Set idProject
     *
     * @param integer $idProject
     *
     * @return Heart
     */
    public function setIdProject($idProject)
    {
        $this->idProject = $idProject;

        return $this;
    }

    /**
     * Get idProject
     *
     * @return int
     */
    public function getIdProject()
    {
        return $this->idProject;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Heart
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
}

