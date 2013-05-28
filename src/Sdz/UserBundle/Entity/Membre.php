<?php
// src/Sdz/UserBundle/Entity/Membre.php

namespace Sdz\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sdz\UserBundle\Entity\Membre
 *
 * @ORM\Table(name="ism_membre")
 * @ORM\Entity(repositoryClass="Sdz\UserBundle\Entity\MembreRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Membre
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(name="website", type="string", length=255)
     */
    private $website;

    /**
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(name="jobTitle", type="string", length=255)
     */
    private $jobTitle;

    /**
     * @ORM\Column(name="jobDescription", type="text", length=255)
     */
    private $jobDescription;

    /**
    * @ORM\Column(name="dateEdition", type="datetime", nullable=true)
    */
    private $dateEdition;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Membre
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
     * @return Membre
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
     * @return string
     */
    public function getFullname()
    {
        return sprintf("%s %s", $this->getFirstname(), $this->getLastname());
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Membre
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Membre
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return Membre
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Membre
     */
    public function setDate(\Datetime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set jobDescription
     *
     * @param string $jobDescription
     * @return Membre
     */
    public function setJobDescription($jobDescription)
    {
        $this->jobDescription = $jobDescription;

        return $this;
    }

    /**
     * Get jobDescription
     *
     * @return string
     */
    public function getJobDescription()
    {
        return $this->jobDescription;
    }

    public function __construct()
    {
        $this->date = new \Datetime;
    }

    /**
    * @ORM\PreUpdate
    * Callback pour mettre à jour la date d'édition à chaque modification de l'entité
    */
    public function updateDate()
    {
        $this->setDateEdition(new \Datetime());
    }

    public function setDateEdition(\Datetime $dateEdition)
    {
        $this->dateEdition = $dateEdition;
    }

    public function getDateEdition()
    {
        return $this->dateEdition;
    }

}
