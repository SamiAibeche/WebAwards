<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\VoteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Vote
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
     * @ORM\Column(name="idProject", type="integer")
     */
    private $idProject;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer")
     */
    private $idUser;

    /**
     * @var float
     *
     * @ORM\Column(name="nbDesign", type="float")
     */
    private $nbDesign;

    /**
     * @var float
     *
     * @ORM\Column(name="nbFluidity", type="float")
     */
    private $nbFluidity;

    /**
     * @var float
     *
     * @ORM\Column(name="nbConcept", type="float")
     */
    private $nbConcept;

    /**
     * @var float
     *
     * @ORM\Column(name="nbContent", type="float")
     */
    private $nbContent;

    /**
     * @var float
     *
     * @ORM\Column(name="nbTotal", type="float")
     */
    private $nbTotal;
    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="votes")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="votes")
     */
    private $user;


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
     * Set idProject
     *
     * @param integer $idProject
     *
     * @return Vote
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
     * @return Vote
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
     * Set nbDesign
     *
     * @param float $nbDesign
     *
     * @return Vote
     */
    public function setNbDesign($nbDesign)
    {
        $this->nbDesign = $nbDesign;

        return $this;
    }

    /**
     * Get nbDesign
     *
     * @return float
     */
    public function getNbDesign()
    {
        return $this->nbDesign;
    }

    /**
     * Set nbFluidity
     *
     * @param float $nbFluidity
     *
     * @return Vote
     */
    public function setNbFluidity($nbFluidity)
    {
        $this->nbFluidity = $nbFluidity;

        return $this;
    }

    /**
     * Get nbFluidity
     *
     * @return float
     */
    public function getNbFluidity()
    {
        return $this->nbFluidity;
    }

    /**
     * Set nbConcept
     *
     * @param float $nbConcept
     *
     * @return Vote
     */
    public function setNbConcept($nbConcept)
    {
        $this->nbConcept = $nbConcept;

        return $this;
    }

    /**
     * Get nbConcept
     *
     * @return float
     */
    public function getNbConcept()
    {
        return $this->nbConcept;
    }

    /**
     * Set nbContent
     *
     * @param float $nbContent
     *
     * @return Vote
     */
    public function setNbContent($nbContent)
    {
        $this->nbContent = $nbContent;

        return $this;
    }

    /**
     * Get nbContent
     *
     * @return float
     */
    public function getNbContent()
    {
        return $this->nbContent;
    }

    /**
     * Set nbTotal
     *
     * @param float $nbTotal
     *
     * @return Vote
     */
    public function setNbTotal($nbTotal)
    {
        $this->nbTotal = $nbTotal;

        return $this;
    }

    /**
     * Get nbTotal
     *
     * @return float
     */
    public function getNbTotal()
    {
        return $this->nbTotal;
    }
}

