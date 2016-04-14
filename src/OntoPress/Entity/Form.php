<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form.
 * This PHP Object allows Doctrine to translate php Objects into an relational SQL Table via Metadata.
 *
 * @ORM\Table(name="ontopress_form")
 * @ORM\Entity(repositoryClass="OntoPress\Repository\FormRepository")
 */
class Form
{
    /**
     * Primary Key.
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name of Form.
     *
     * @var string
     * @ORM\Column(name="name", type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    protected $name;

    /**
     * @var Ontology
     * @ORM\ManyToOne(targetEntity="Ontology", inversedBy="ontologyForms")
     * @ORM\JoinColumn(name="ontology_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $ontology;

    /**
     * Ontology Fields.
     *
     * @var OntologyField
     * @ORM\ManyToMany(targetEntity="OntologyField")
     * @ORM\JoinTable(name="ontopress_form_field",
     *      joinColumns={@ORM\JoinColumn(name="form_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ontology_field_id", referencedColumnName="id")}
     *      )
     */
    protected $ontologyFields;

    /**
     * Twig Code.
     *
     * @var string
     * @ORM\Column(name="twig_code", type="text")
     */
    protected $twigCode;

    /**
     * Wordpress user who created the Form.
     *
     * @var string
     * @ORM\Column(name="author", type="string", length=32)
     * @Assert\NotBlank()
     */
    protected $author;

    /**
     * Upload date.
     *
     * @var DateTime
     * @ORM\Column(name="upload_date", type="datetime")
     * @Assert\NotBlank()
     */
    protected $date;

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
     * @return Ontology
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
     * Setter ontology.
     * Sets the Ontology for the many to one connection to Ontologys.
     * Default NULL.
     *
     * @param \OntoPress\Entity\Ontology $ontology
     *
     * @return Form
     */
    public function setOntology(\OntoPress\Entity\Ontology $ontology = null)
    {
        $this->ontology = $ontology;

        return $this;
    }

    /**
     * Get Ontology.
     * Returns the Ontology, that this Object is related to.
     *
     * @return int
     */
    public function getOntology()
    {
        return $this->ontology;
    }

    /**
     * Set Twig Code.
     *
     * @param string $twigCode
     *
     * @return Form
     */
    public function setTwigCode($twigCode)
    {
        $this->twigCode = $twigCode;

        return $this;
    }

    /**
     * Get Twig Code.
     * Returns the Twig Code of this Object.
     *
     * @return string
     */
    public function getTwigCode()
    {
        return $this->twigCode;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return Form
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Form
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Form constructor.
     * This Constructor is automatically called by creating a new Form Object.
     * It initializes the OntologyFields as ArrayCollection.
     */
    public function __construct()
    {
        $this->ontologyFields = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Adds an OntologyField to the array collection of OntologyFields.
     *
     * @param \OntoPress\Entity\OntologyField $ontologyField
     *
     * @return Form
     */
    public function addOntologyField(\OntoPress\Entity\OntologyField $ontologyField)
    {
        $this->ontologyFields[] = $ontologyField;

        return $this;
    }

    /**
     * Removes one OntologyField from OntologyField array collection.
     *
     * @param \OntoPress\Entity\OntologyField $ontologyField
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOntologyField(\OntoPress\Entity\OntologyField $ontologyField)
    {
        return $this->ontologyFields->removeElement($ontologyField);
    }

    /**
     * Get ontologyField.
     * Returns all OntologyFields, of this Object.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOntologyFields()
    {
        return $this->ontologyFields;
    }
}
