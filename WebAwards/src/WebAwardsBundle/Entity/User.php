<?php

namespace WebAwardsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="WebAwardsBundle\Repository\UserRepository")
 */
class User
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
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=125)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=125)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_birthday", type="datetime")
     */
    private $dateBirthday;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=125)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=125)
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
     * @ORM\Column(name="is_publisher", type="boolean")
     */
    private $isPublisher;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_subscribe", type="boolean")
     */
    private $isSubscribe;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_admin", type="boolean")
     */
    private $isAdmin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_aff", type="datetime")
     */
    private $dateAff;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=1)
     */
    private $sexe;


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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
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
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set dateBirthday
     *
     * @param \DateTime $dateBirthday
     *
     * @return User
     */
    public function setDateBirthday($dateBirthday)
    {
        $this->dateBirthday = $dateBirthday;

        return $this;
    }

    /**
     * Get dateBirthday
     *
     * @return \DateTime
     */
    public function getDateBirthday()
    {
        return $this->dateBirthday;
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
        $this->dateAff = $dateAff;

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

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return User
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }
}

