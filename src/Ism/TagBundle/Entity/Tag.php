<?php

namespace Ism\TagBundle\Entity;

use \FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ism\TagBundle\Entity\Tag
 *
 * @ORM\Table(name="ism_tag")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="DoctrineExtensions\Taggable\Entity\TagRepository")
 */
class Tag extends BaseTag
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tagging", mappedBy="tag", fetch="EAGER")
     **/
    protected $tagging;
}
