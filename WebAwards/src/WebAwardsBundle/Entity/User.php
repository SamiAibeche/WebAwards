<?php

namespace WebAwardsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=125)
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=125)
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdayAt", type="datetime")
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     */
    private $birthdayAt;

    /**
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=125)
     * @Assert\NotBlank(message="Ce champs ne devrait pas être vide")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=125)
     * @Assert\Image(
     *     mimeTypes={ "image/png", "image/jpg", "image/jpeg" },
     *     mimeTypesMessage="Seuls les formats png, jpg, jpeg sont acceptés.",
     *     minWidth = 340,
     *     maxWidth = 360,
     *     minHeight = 340,
     *     maxHeight = 360,
     *     maxWidthMessage = "L'image principale doit avoir une largeur de 350 px et une hauteur de 350 px",
     *     minWidthMessage = "L'image principale doit avoir une largeur de 350 px et une hauteur de 350 px " ,
     *     maxHeightMessage = "L'image principale doit avoir une largeur de 350 px et une hauteur de 350 px ",
     *     minHeightMessage = "L'image principale doit avoir une largeur de 350 px et une hauteur de 350 px "
     * )
     * @Assert\NotBlank(message="Veuillez choisir une image de profil ")
     *
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=40)
     */
    private $role;

    /**
     * @var bool
     *
     * @ORM\Column(name="isPublisher", type="boolean")
     */
    private $isPublisher;

    /**
     * @var bool
     *
     * @ORM\Column(name="isSubscribe", type="boolean")
     */
    private $isSubscribe;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAdmin", type="boolean")
     */
    private $isAdmin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAff", type="datetime")
     */
    private $dateAff;


    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="idUser", cascade={"remove"})
     */
    private $idUsers;
    
    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="idUser" ,cascade={"remove"})
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="Heart", mappedBy="idUser", cascade={"remove"})
     */
    private $hearts;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="idAuthor", cascade={"remove"})
     */
    private $projects;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthdayAt
     *
     * @param \DateTime $birthdayAt
     *
     * @return User
     */
    public function setBirthdayAt($birthdayAt)
    {
        $this->birthdayAt = $birthdayAt;

        return $this;
    }

    /**
     * Get birthdayAt
     *
     * @return \DateTime
     */
    public function getBirthdayAt()
    {
        return $this->birthdayAt;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return User
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }


    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set isPublisher
     *
     * @param boolean $isPublisher
     *
     * @return User
     */
    public function setIsPublisher($isPublisher)
    {
        $this->isPublisher = $isPublisher;

        return $this;
    }

    /**
     * Get isPublisher
     *
     * @return bool
     */
    public function getIsPublisher()
    {
        return $this->isPublisher;
    }

    /**
     * Set isSubscribe
     *
     * @param boolean $isSubscribe
     *
     * @return User
     */
    public function setIsSubscribe($isSubscribe)
    {
        $this->isSubscribe = $isSubscribe;

        return $this;
    }

    /**
     * Get isSubscribe
     *
     * @return bool
     */
    public function getIsSubscribe()
    {
        return $this->isSubscribe;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set dateAff
     *
     * @param \DateTime $dateAff
     *
     * @return User
     */
    public function setDateAff($dateAff)
    {
        $this->dateAff = new \DateTime($dateAff);

        return $this;
    }

    /**
     * Get dateAff
     *
     * @return \DateTime
     */
    public function getDateAff()
    {
        return $this->dateAff;
    }


    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function __toString()
    {
        return "".$this->getId();
    }
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }


}

