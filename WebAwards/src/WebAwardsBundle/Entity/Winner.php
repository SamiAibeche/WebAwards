<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winners
 *
 * @ORM\Table(name="winner")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\WinnerRepository")
 */
class Winners
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
     * @var bool
     *
     * @ORM\Column(name="is_day", type="boolean")
     */
    private $isDay;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_week", type="boolean")
     */
    private $isWeek;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_month", type="boolean")
     */
    private $isMonth;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_year", type="boolean")
     */
    private $isYear;


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
     * @return Winners
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
     * Set isDay
     *
     * @param boolean $isDay
     *
     * @return Winners
     */
    public function setIsDay($isDay)
    {
        $this->isDay = $isDay;

        return $this;
    }

    /**
     * Get isDay
     *
     * @return bool
     */
    public function getIsDay()
    {
        return $this->isDay;
    }

    /**
     * Set isWeek
     *
     * @param boolean $isWeek
     *
     * @return Winners
     */
    public function setIsWeek($isWeek)
    {
        $this->isWeek = $isWeek;

        return $this;
    }

    /**
     * Get isWeek
     *
     * @return bool
     */
    public function getIsWeek()
    {
        return $this->isWeek;
    }

    /**
     * Set isMonth
     *
     * @param boolean $isMonth
     *
     * @return Winners
     */
    public function setIsMonth($isMonth)
    {
        $this->isMonth = $isMonth;

        return $this;
    }

    /**
     * Get isMonth
     *
     * @return bool
     */
    public function getIsMonth()
    {
        return $this->isMonth;
    }

    /**
     * Set isYear
     *
     * @param boolean $isYear
     *
     * @return Winners
     */
    public function setIsYear($isYear)
    {
        $this->isYear = $isYear;

        return $this;
    }

    /**
     * Get isYear
     *
     * @return bool
     */
    public function getIsYear()
    {
        return $this->isYear;
    }
}

