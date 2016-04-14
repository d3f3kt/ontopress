<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DataOntology
 * This PHP Object allows Doctrine to translate php Objects into an relational SQL Table via Metadata.
 *
 * @ORM\Table(name="ontopress_dataOntology")
 * @ORM\Entity()
 */
class DataOntology
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Assert\NotBlank()
     */
    protected $id;

    /**
     * @var OntologyField
     * @ORM\OneToMany(targetEntity="OntologyField", mappedBy="dataOntology", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $ontologyFields;

    /**
     * @var Ontology
     * @ORM\ManyToOne(targetEntity="Ontology", inversedBy="dataOntologies")
     */
    protected $ontology;

    /**
     * @var String
     * @ORM\Column(name="name", type="string", length=128)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    protected $name;

    /**
     * DataOntology constructor.
     * This Constructor is automatically called by creating a new DataOntology Object.
     * It initializes the OntologyFields as ArrayCollection.
     */
    public function __construct()
    {
        $this->ontologyFields = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Setter ontology.
     * Sets the Ontology for the many to one connection to Ontologys.
     * Default NULL.
     *
     * @param Ontology|null $ontology
     * @return DataOntology
     */
    public function setOntology(\OntoPress\Entity\Ontology $ontology = null)
    {
        $this->ontology = $ontology;

        return $this;
    }

    /**
     * Getter ontology.
     * Returns the Ontology, that this Object is related to.
     *
     * @return Ontology
     */
    public function getOntology()
    {
        return $this->ontology;
    }

    /**
     * Getter ontologyFields.
     * Returns all OntologyFields, of this Object.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|OntologyField
     */
    public function getOntologyFields()
    {
        return $this->ontologyFields;
    }

    /**
     * Adds an OntologyField to the array collection of OntologyFields.
     * @param OntologyField $newOntologyField
     * @return DataOntology
     */
    public function addOntologyField(\OntoPress\Entity\OntologyField $newOntologyField)
    {
        $newOntologyField->setDataOntology($this);
        $this->ontologyFields[] = $newOntologyField;

        return $this;
    }

    /**
     * Removes one OntologyField from OntologyField array collection.
     * @param \OntoPress\Entity\OntologyField $ontologyField
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOntologyField(\OntoPress\Entity\OntologyField $ontologyField)
    {
        return $this->ontologyFields->removeElement($ontologyField);
    }

    /**
     * Getter name.
     * Returns the Name of this Object
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter name.
     * Sets the Name of this Object
     *
     * @param String $newName
     * @return $this
     */
    public function setName($newName)
    {
        $this->name = $newName;

        return $this;
    }
}
