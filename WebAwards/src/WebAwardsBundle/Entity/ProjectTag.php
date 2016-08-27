<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectTag
 *
 * @ORM\Table(name="project_tag")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\ProjectTagRepository")
 */
class ProjectTag
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
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="idTags")
     */
    private $idTag;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="idProjects")
     */
    private $idProject;


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
     * Set tag
     *
     * @param string $idTag
     *
     * @return idTag
     */
    public function setIdTag($idTag)
    {
        $this->idTags = $idTag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getIdTag()
    {
        return $this->idTag;
    }


    /**
     * @return mixed
     */
    public function getIdProject()
    {
        return $this->idProject;
    }

    /**
     * @param mixed $idProject
     */
    public function setProject($idProject)
    {
        $this->idProject = $idProject;
    }

}

