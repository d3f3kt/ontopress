<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Restriction.
 * This PHP Object allows Doctrine to translate a Restriction into an relational SQL Table via Metadata.
 *
 * @ORM\Table(name="ontopress_restriction")
 * @ORM\Entity()
 */
class Restriction
{
    /**
     * Primary Key
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var OntologyField
     * @ORM\ManyToOne(targetEntity="OntologyField", inversedBy="restriction")
     * @ORM\JoinColumn(name="ontologyField_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $ontologyField;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=256)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * Getter id.
     * Returns the Primary key "id"
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Name of this Object
     *
     * @param string $name
     *
     * @return Restriction
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name.
     * Returns the Name of this Object

     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter OntologyField.
     * Sets the OntologyField for the many to one connection to Ontologys.
     * Default NULL.
     *
     * @param \OntoPress\Entity\OntologyField $ontologyField
     *
     * @return Restriction
     */
    public function setOntologyField(\OntoPress\Entity\OntologyField $ontologyField = null)
    {
        $this->ontologyField = $ontologyField;

        return $this;
    }

    /**
     * Get ontologyField
     * Returns the OntologyField, that this Object is related to.
     *
     * @return \OntoPress\Entity\OntologyField
     */
    public function getOntologyField()
    {
        return $this->ontologyField;
    }
}
