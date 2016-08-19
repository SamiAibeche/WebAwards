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
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="tag")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="project")
     */
    private $projects;
    

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
     * Set project
     *
     * @param string $project
     *
     * @return ProjectTag
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return ProjectTag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
}

