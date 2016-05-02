<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OntologyField.
 * This PHP Object allows Doctrine to translate php Objects into an relational SQL Table via Metadata.
 *
 * @ORM\Table(name="ontopress_ontology_field")
 * @ORM\Entity()
 */
class OntologyField
{
    const TYPE_TEXT = 'TEXT';
    const TYPE_RADIO = 'RADIO';
    const TYPE_CHOICE = 'CHOICE';
    const TYPE_SELECT = 'RADIO_SELECT';

    /**
     * @var DataOntology
     * @ORM\ManyToOne(targetEntity="DataOntology", inversedBy="ontologyFields")
     */
    protected $dataOntology;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=256)
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
     * @ORM\Column(name="label", type="string", length=256, nullable=true)
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string", length=512, nullable=true)
     */
    protected $comment;

    /**
     * @var bool
     * @ORM\Column(name="mandatory", type="boolean", nullable=true)
     */
    protected $mandatory;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=32, nullable=true)
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(name="regex", type="string", length=64, nullable=true)
     */
    protected $regex;

    /**
     * @var Restriction
     * @ORM\OneToMany(targetEntity="Restriction", mappedBy="ontologyField", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $restrictions;

    /**
     * @var bool
     */
    protected $possessed;

    /**
     * OntologyField constructor.
     * This Constructor is automatically called by creating a new OntologyField Object.
     * It initializes the Restrictions as ArrayCollection.
     */
    public function __construct()
    {
        $this->restrictions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Getter Uri File
     * Returns the last part of an Uri.
     *
     * @return string last part of the Uri
     */
    public function getUriFile()
    {
        $parts = explode('/', $this->getName());
        $revParts = array_reverse($parts);

        return $revParts[0];
    }

    /**
     * Generates and Returns an unique Name of this Ontology Field Object.
     * It replaces every special character with a "_"
     *
     * @return string
     */
    public function getFormFieldName()
    {
        return 'OntologyField_' . $this->getId();
    }

    /**
     * Removes one Restriction from the Restriction array collection.
     *
     * @param Restriction $restriction
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRestriction(\OntoPress\Entity\Restriction $restriction)
    {
        return $this->restrictions->removeElement($restriction);
    }

    /**
     * Adds a Restriction to the array collection of Restrictions.
     *
     * @param Restriction $newRestriction
     *
     * @return OntologyField
     */
    public function addRestriction(\OntoPress\Entity\Restriction $newRestriction)
    {
        $newRestriction->setOntologyField($this);
        $this->restrictions[] = $newRestriction;

        return $this;
    }

    /**
     * Getter id.
     * Returns the Primary key "id"
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Name of this Object
     *
     * @param String $name
     *
     * @return OntologyField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter name.
     * Returns the Name of this Object
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter label.
     *
     * @param String $label
     *
     * @return OntologyField
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Getter label.
     *
     * @return string $label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Setter comment.
     *
     * @param String $comment
     *
     * @return OntologyField
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Getter comment.
     *
     * @return string $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Setter mandatory.
     *
     * @param bool $mandatory
     *
     * @return OntologyField
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * Getter mandatory.
     *
     * @return bool
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Getter type.
     *
     * @param string $type
     *
     * @return OntologyField
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Getter type.
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Getter restrictions.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|Restriction
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * Setter DataOntology.
     * Sets the DataOntology for the many to one connection to DataOntologys.
     * Default NULL.
     *
     * @param DataOntology|null $dataOntology
     *
     * @return OntologyField
     */
    public function setDataOntology(\OntoPress\Entity\DataOntology $dataOntology = null)
    {
        $this->dataOntology = $dataOntology;

        return $this;
    }

    /**
     * Getter dataOntology.
     *
     * @return DataOntology
     */
    public function getDataOntology()
    {
        return $this->dataOntology;
    }

    /**
     * Setter possessed.
     *
     * @param bool $possessed
     *
     * @return OntologyField
     */
    public function setPossessed($possessed)
    {
        $this->possessed = $possessed;

        return $this;
    }

    /**
     * Getter possessed.
     *
     * @return bool
     */
    public function getPossessed()
    {
        return $this->possessed;
    }

    /**
     * Set regex
     *
     * @param string $regex
     *
     * @return OntologyField
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get regex
     *
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }
}
