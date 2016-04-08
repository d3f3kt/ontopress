<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OntologyNode.
 *
 * @ORM\Table(name="ontopress_ontology_field")
 * @ORM\Entity()
 */
class OntologyField
{
    const TYPE_TEXT = 'Text';
    const TYPE_RADIO = 'Radio-Button';
    const TYPE_CHOICE = 'Restriction-Choice';

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
     * @var Restriction
     * @ORM\OneToMany(targetEntity="Restriction", mappedBy="ontologyField", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $restrictions;

    /**
     * @var bool
     */
    protected $possessed;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->restrictions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get last part of an Uri.
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
     * Helper function to generate unique name from ontology field object.
     *
     * @return string
     */
    public function getFormFieldName()
    {
        return str_replace(' ', '', $this->getDataOntology()->getOntology()->getName())
            .'_'
            .preg_replace("/[^A-Za-z0-9 ]/", '_', $this->getUriFile());
    }

    /**
     * Removes a restriction from $restrictions.
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
     * Adds a restriction to $restrictions.
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
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter name.
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
     * Setter dataOntology.
     *
     * @param Ontology|null $dataOntology
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
}
