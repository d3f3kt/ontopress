<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// @UniqueEntity(fields={"name"}, message="Dieser Ontologiename existiert bereits.")
/**
 * Ontology.
 * This PHP Object allows Doctrine to translate php Objects into an relational SQL Table via Metadata.
 *
 *
 * @ORM\Table(name="ontopress_ontology")
 * @ORM\Entity(repositoryClass="OntoPress\Repository\OntologyRepository")
 */
class Ontology
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

    // unique=true
    /**
     * Name of ontology.
     *
     * @var string
     * @ORM\Column(name="name", type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    protected $name;

    /**
     * Ontology Files.
     *
     * @var OntologyFile
     * @ORM\OneToMany(targetEntity="OntologyFile", mappedBy="ontology", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $ontologyFiles;

    /**
     * Ontology Forms.
     *
     * @var Form
     * @ORM\OneToMany(targetEntity="Form", mappedBy="ontology", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $ontologyForms;

    /**
     * @var DataOntology
     * @ORM\OneToMany(targetEntity="DataOntology", mappedBy="ontology", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $dataOntologies;

    /**
     * Wordpress user who created the ontology.
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
     *
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
     * Set author.
     *
     * @param string $author
     *
     * @return Ontology
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
     * @return Ontology
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
     * Ontology constructor.
     * This Constructor is automatically called by creating a new Ontology Object.
     * It initializes the OntologyFiles, OntologyForm, DataOntologies as ArrayCollection.
     */
    public function __construct()
    {
        $this->ontologyFiles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ontologyForms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dataOntologies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Adds an OntologyFile to the array collection of OntologyFiles.
     *
     * @param \OntoPress\Entity\OntologyFile $ontologyFile
     *
     * @return Ontology
     */
    public function addOntologyFile(\OntoPress\Entity\OntologyFile $ontologyFile)
    {
        $ontologyFile->setOntology($this);
        $this->ontologyFiles[] = $ontologyFile;

        return $this;
    }

    /**
     * Removes one OntologyFile from OntologyFile array collection.
     *
     * @param \OntoPress\Entity\OntologyFile $ontologyFile
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOntologyFile(\OntoPress\Entity\OntologyFile $ontologyFile)
    {
        return $this->ontologyFiles->removeElement($ontologyFile);
    }

    /**
     * Get ontologyFiles.
     * Returns all OntologyFiles, of this Object.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOntologyFiles()
    {
        return $this->ontologyFiles;
    }

    /**
     * Adds an OntologyForm to the array collection of OntologyForm.
     *
     * @param \OntoPress\Entity\Form $ontologyForm
     *
     * @return Ontology
     */
    public function addOntologyForm(\OntoPress\Entity\Form $ontologyForm)
    {
        $ontologyForm->setOntology($this);
        $this->ontologyForms[] = $ontologyForm;

        return $this;
    }

    /**
     * Removes one OntologyForm from OntologyForm array collection.
     *
     * @param \OntoPress\Entity\Form $ontologyForm
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOntologyForm(\OntoPress\Entity\Form $ontologyForm)
    {
        return $this->ontologyForms->removeElement($ontologyForm);
    }

    /**
     * Get ontologyForms.
     * Returns all OntologyForms, of this Object.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOntologyForms()
    {
        return $this->ontologyForms;
    }

    /**
     * Adds an DataOntology to the array collection of DataOntologys.
     *
     * @param \OntoPress\Entity\DataOntology $newDataOntology
     *
     * @return Ontology
     */
    public function addDataOntology(\OntoPress\Entity\DataOntology $newDataOntology)
    {
        $newDataOntology->setOntology($this);
        $this->dataOntologies[] = $newDataOntology;

        return $this;
    }

    /**
     * Removes one OntologyForm from OntologyForm array collection.
     *
     * @param \OntoPress\Entity\DataOntology $dataOntology
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDataOntology(\OntoPress\Entity\DataOntology $dataOntology)
    {
        return $this->dataOntologies->removeElement($dataOntology);
    }

    /**
     * Get DataOntologies
     * Returns all DataOntologies, of this Object.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDataOntologies()
    {
        return $this->dataOntologies;
    }

    /**
     *Uploads every Ontology File
     */
    public function uploadFiles()
    {
        foreach ($this->getOntologyFiles() as $file) {
            $file->upload();
        }
    }
}
