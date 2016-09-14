<?php

namespace WebAwardsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="projects")
     * @ORM\JoinColumn(name="idAuthor", referencedColumnName="id")
     */
    private $idAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=125)
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="imgScreen", type="string", length=255)
     * @Assert\Image(
     *     mimeTypes={ "image/png", "image/jpg", "image/jpeg" },
     *     mimeTypesMessage="Seuls les formats png, jpg, jpeg sont acceptés.",
     *     minWidth = 690,
     *     maxWidth = 710,
     *     minHeight = 490,
     *     maxHeight = 510,
     *     maxWidthMessage = "L'image principale doit avoir une largeur de 700 px et une hauteur de 500 px",
     *     minWidthMessage = "L'image principale doit avoir une largeur de 700 px et une hauteur de 500 px " ,
     *     maxHeightMessage = "L'image principale doit avoir une largeur de 700 px et une hauteur de 500 px ",
     *     minHeightMessage = "L'image principale doit avoir une largeur de 700 px et une hauteur de 500 px "
     * )
     * @Assert\NotBlank(message="Veuillez choisir une image laptop ")
     */
    private $imgScreen;

    /**
     * @var string
     *
     * @ORM\Column(name="imgMobile", type="string", length=255)
     * @Assert\Image(
     *     mimeTypes={ "image/png", "image/jpg", "image/jpeg" },
     *     mimeTypesMessage="Seuls les formats png, jpg, jpeg sont acceptés.",
     *     minWidth = 500,
     *     maxWidth = 520,
     *     minHeight = 900,
     *     maxHeight = 920,
     *     maxWidthMessage = "L'image mobile doit avoir une largeur de 510 px et une hauteur de 910px",
     *     minWidthMessage = "L'image mobile doit avoir une largeur de 510 px et une hauteur de 910px" ,
     *     maxHeightMessage = "L'image mobile doit avoir une largeur de 510 px et une hauteur de 910px",
     *     minHeightMessage = "L'image mobile doit avoir une largeur de 510 px et une hauteur de 910px"
     * )
     * @Assert\NotBlank(message="Veuillez choisir une image mobile")
     */
    private $imgMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=125)
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     */
    private $url;

    /**
     * @var int
     *
     * @ORM\Column(name="nbLike", type="integer")
     */
    private $nbLike;

    /**
     * @var bool
     *
     * @ORM\Column(name="isForward", type="boolean")
     */
    private $isForward;

    /**
     * @var bool
     *
     * @ORM\Column(name="isVisible", type="boolean")
     */
    private $isVisible;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdd", type="datetime")
     */
    private $dateAdd;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="idProject",cascade={"remove"})
     */
    private $votes;
    
    /**
     * @ORM\OneToMany(targetEntity="Heart", mappedBy="idProject",cascade={"remove"})
     */
    private $hearts;

    /**
     * @ORM\OneToMany(targetEntity="Winner", mappedBy="idProject",cascade={"remove"})
     */
    private $winners;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="ProjectTag", mappedBy="idProject",cascade={"remove"})
     */
    private $idProjects;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="idProject",cascade={"remove"})
     */
    private $idComments;

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
     * Set idAuthor
     *
     * @param integer $idAuthor
     *
     * @return Project
     */
    public function setIdAuthor($idAuthor)
    {
        $this->idAuthor = $idAuthor;

        return $this;
    }

    /**
     * Get idAuthor
     *
     * @return int
     */
    public function getIdAuthor()
    {
        return $this->idAuthor;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Project
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
     * @return Project
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
     * @return Project
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
     * @return Project
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
     * @return Project
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
     * Set nbLike
     *
     * @param integer $nbLike
     *
     * @return Project
     */
    public function setNbLike($nbLike)
    {
        $this->nbLike = $nbLike;

        return $this;
    }

    /**
     * Get nbLike
     *
     * @return int
     */
    public function getNbLike()
    {
        return $this->nbLike;
    }

    /**
     * Set isForward
     *
     * @param boolean $isForward
     *
     * @return Project
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
     * @return Project
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
     * @return Project
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
    public function __toString()
    {
        return "".$this->getId();
    }
}

