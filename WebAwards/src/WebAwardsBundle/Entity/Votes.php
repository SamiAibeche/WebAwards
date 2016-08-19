<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Votes
 *
 * @ORM\Table(name="votes")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\VotesRepository")
 */
class Votes
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
     * @ORM\Column(name="id_project", type="integer")
     */
    private $idProject;

    /**
     * @var float
     *
     * @ORM\Column(name="count_design", type="float")
     */
    private $countDesign;

    /**
     * @var float
     *
     * @ORM\Column(name="count_fluidity", type="float")
     */
    private $countFluidity;

    /**
     * @var float
     *
     * @ORM\Column(name="count_concept", type="float")
     */
    private $countConcept;

    /**
     * @var float
     *
     * @ORM\Column(name="count_content", type="float")
     */
    private $countContent;

    /**
     * @var float
     *
     * @ORM\Column(name="count_total", type="float")
     */
    private $countTotal;


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
     * @return Votes
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
     * Set countDesign
     *
     * @param float $countDesign
     *
     * @return Votes
     */
    public function setCountDesign($countDesign)
    {
        $this->countDesign = $countDesign;

        return $this;
    }

    /**
     * Get countDesign
     *
     * @return float
     */
    public function getCountDesign()
    {
        return $this->countDesign;
    }

    /**
     * Set countFluidity
     *
     * @param float $countFluidity
     *
     * @return Votes
     */
    public function setCountFluidity($countFluidity)
    {
        $this->countFluidity = $countFluidity;

        return $this;
    }

    /**
     * Get countFluidity
     *
     * @return float
     */
    public function getCountFluidity()
    {
        return $this->countFluidity;
    }

    /**
     * Set countConcept
     *
     * @param float $countConcept
     *
     * @return Votes
     */
    public function setCountConcept($countConcept)
    {
        $this->countConcept = $countConcept;

        return $this;
    }

    /**
     * Get countConcept
     *
     * @return float
     */
    public function getCountConcept()
    {
        return $this->countConcept;
    }

    /**
     * Set countContent
     *
     * @param string $countContent
     *
     * @return Votes
     */
    public function setCountContent($countContent)
    {
        $this->countContent = $countContent;

        return $this;
    }

    /**
     * Get countContent
     *
     * @return string
     */
    public function getCountContent()
    {
        return $this->countContent;
    }

    /**
     * Set countTotal
     *
     * @param float $countTotal
     *
     * @return Votes
     */
    public function setCountTotal($countTotal)
    {
        $this->countTotal = $countTotal;

        return $this;
    }

    /**
     * Get countTotal
     *
     * @return float
     */
    public function getCountTotal()
    {
        return $this->countTotal;
    }
}

