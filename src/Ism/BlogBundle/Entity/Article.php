<?php

namespace Ism\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Taggable\Taggable;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 *
 * @ORM\Table(name="ism_article")
 * @ORM\Entity(repositoryClass="Ism\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article implements Taggable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publication", type="boolean")
     */
    private $publication;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEdition", type="datetime", nullable=true)
     */
    private $dateEdition;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity="Ism\BlogBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Ism\BlogBundle\Entity\Categorie", cascade={"persist"})
     * @ORM\JoinTable(name="ism_article_categorie")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="Ism\UserBundle\Entity\User")
     */
    private $user;

    private $tags;

    public function __construct()
    {
        $this->publication  = true;
        $this->date         = new \Datetime;
        $this->categories   = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
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
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     * @return Article
     */
    public function setDateEdition(\Datetime $dateEdition)
    {
        $this->dateEdition = $dateEdition;

        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime
     */
    public function getDateEdition()
    {
        return $this->dateEdition;
    }

    /**
     * @ORM\PreUpdate
     * Callback pour mettre à jour la date d'édition à chaque modification de l'entité
     */
    public function updateDate()
    {
        $this->setDateEdition(new \Datetime());
    }

    public function setImage(\Ism\BlogBundle\Entity\Image $image = null)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function addCategorie(\Ism\BlogBundle\Entity\Categorie $categorie)
    {
        $this->categories[] = $categorie;
    }

    public function removeCategorie(\Ism\BlogBundle\Entity\Categorie $categorie)
    {
        $this->categories->removeElement($categorie);
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setUser(\Ism\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return 'acme_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }
}
