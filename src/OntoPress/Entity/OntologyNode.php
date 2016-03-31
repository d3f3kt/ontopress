<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OntologyNode
 *
 * @ORM\Table(name="ontopress_ontologyNode")
 * @ORM\Entity()
 */
class OntologyNode
{
    /**
     * @var OntologyFile
     * @ORM\ManyToOne(targetEntity="OntologyFile", inversedBy="ontologyNodes")
     */
    protected $ontologyFile;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=32)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    protected $name;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Assert\NotBlank()
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="label", type="string", length=128)
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string", length=128)
     */
    protected $comment;

    /**
     * @var boolean
     * @ORM\Column(name="mandatory", type="boolean")
     */
    protected $mandatory;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=32)
     */
    protected $type;

    /**
     * @var Restriction
     * @ORM\OneToMany(targetEntity="Restriction", mappedBy="ontologyNode", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $restrictions;

    public function __construct()
    {
        $this->restrictions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function removeRestriction(\OntoPress\Entity\Restriction $restriction)
    {
        return $this->restrictions->removeElement($restriction);
    }

    public function addRestriction(\OntoPress\Entity\Restriction $newRestriction)
    {
        $newRestriction->setOntologyNode($this);
        $this->restrictions[] = $newRestriction;
    }

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
     * Set ontologyFile
     *
     * @param \OntoPress\Entity\OntologyFile $ontologyFile
     *
     * @return OntologyNode
     */
    public function setOntologyFile(\OntoPress\Entity\OntologyFile $ontologyFile = null)
    {
        $this->ontologyFile = $ontologyFile;

        return $this;
    }

    /**
     * Get ontologyFile
     *
     * @return \OntoPress\Entity\OntologyFile
     */
    public function getOntologyFile()
    {
        return $this->ontologyFile;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return OntologyNode
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
     * Set label
     *
     * @param string $label
     *
     * @return OntologyNode
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return OntologyNode
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set mandatory
     *
     * @param bool $mandatory
     *
     * @return OntologyNode
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * Get mandatory
     *
     * @return bool
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return OntologyNode
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get restrictions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }
}
