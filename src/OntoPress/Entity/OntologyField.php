<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OntologyNode
 *
 * @ORM\Table(name="ontopress_ontology_field")
 * @ORM\Entity()
 */
class OntologyField
{
    /**
     * @var DataOntology
     * @ORM\ManyToOne(targetEntity="DataOntology", inversedBy="ontologyFields")
     */
    protected $dataOntology;

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
     * @ORM\Column(name="label", type="string", length=128, nullable=true)
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string", length=128, nullable=true)
     */
    protected $comment;

    /**
     * @var boolean
     * @ORM\Column(name="mandatory", type="boolean", nullable=true)
     */
    protected $mandatory;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=32, nullable=true)
     */
    protected $type;

    /**
     * @var Restriction
     * @ORM\OneToMany(targetEntity="Restriction", mappedBy="ontologyField", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $restrictions;

    /**
     * Constructor.
     */
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
        $newRestriction->setOntologyField($this);
        $this->restrictions[] = $newRestriction;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRestrictions()
    {
        return $this->restrictions;
    }

    public function setDataOntology(\OntoPress\Entity\Ontology $dataOntology = null)
    {
        $this->dataOntology = $dataOntology;

        return $this;
    }

    public function getDataOntology()
    {
        return $this->dataOntology;
    }


}
