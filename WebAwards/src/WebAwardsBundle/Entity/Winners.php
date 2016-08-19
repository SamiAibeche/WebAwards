<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winners
 *
 * @ORM\Table(name="winners")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\WinnersRepository")
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
     * @ORM\Column(name="day", type="boolean")
     */
    private $day;

    /**
     * @var bool
     *
     * @ORM\Column(name="week", type="boolean")
     */
    private $week;

    /**
     * @var bool
     *
     * @ORM\Column(name="month", type="boolean")
     */
    private $month;

    /**
     * @var bool
     *
     * @ORM\Column(name="year", type="boolean")
     */
    private $year;


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
     * Set day
     *
     * @param boolean $day
     *
     * @return Winners
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return bool
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set week
     *
     * @param boolean $week
     *
     * @return Winners
     */
    public function setWeek($week)
    {
        $this->week = $week;

        return $this;
    }

    /**
     * Get week
     *
     * @return bool
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Set month
     *
     * @param boolean $month
     *
     * @return Winners
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return bool
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param boolean $year
     *
     * @return Winners
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return bool
     */
    public function getYear()
    {
        return $this->year;
    }
}

