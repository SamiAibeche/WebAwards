<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projects
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\ProjectsRepository")
 */
class Projects
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=125)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="img_screen", type="string", length=255)
     */
    private $imgScreen;

    /**
     * @var string
     *
     * @ORM\Column(name="img_mobile", type="string", length=255)
     */
    private $imgMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=125)
     */
    private $url;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_forward", type="boolean")
     */
    private $isForward;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_visible", type="boolean")
     */
    private $isVisible;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;


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
     * @return Projects
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
     * Set name
     *
     * @param string $name
     *
     * @return Projects
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Projects
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgScreen
     *
     * @param string $imgScreen
     *
     * @return Projects
     */
    public function setImgScreen($imgScreen)
    {
        $this->imgScreen = $imgScreen;

        return $this;
    }

    /**
     * Get imgScreen
     *
     * @return string
     */
    public function getImgScreen()
    {
        return $this->imgScreen;
    }

    /**
     * Set imgMobile
     *
     * @param string $imgMobile
     *
     * @return Projects
     */
    public function setImgMobile($imgMobile)
    {
        $this->imgMobile = $imgMobile;

        return $this;
    }

    /**
     * Get imgMobile
     *
     * @return string
     */
    public function getImgMobile()
    {
        return $this->imgMobile;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Projects
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set isForward
     *
     * @param boolean $isForward
     *
     * @return Projects
     */
    public function setIsForward($isForward)
    {
        $this->isForward = $isForward;

        return $this;
    }

    /**
     * Get isForward
     *
     * @return bool
     */
    public function getIsForward()
    {
        return $this->isForward;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return Projects
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return bool
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Projects
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }
}

