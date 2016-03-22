<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ontology.
 *
 * @ORM\Table(name="wp_ontology")
 * @ORM\Entity()
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

    /**
     * Name of ontology.
     *
     * @var string
     * @ORM\Column(name="name", type="string", length=64)
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
     * Wordpress user who created the ontology.
     *
     * @var string
     * @ORM\Column(name="author", type="string", length=32)
     */
    protected $author;

    /**
     * Upload date.
     *
     * @var DateTime
     * @ORM\Column(name="upload_date", type="datetime")
     */
    protected $date;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
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
     * Constructor.
     */
    public function __construct()
    {
        $this->ontologyFiles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ontologyFile.
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
     * Remove ontologyFile.
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
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOntologyFiles()
    {
        return $this->ontologyFiles;
    }

    /**
     *Upload every Ontology File
     */
    public function uploadFiles()
    {
        foreach ($this->getOntologyFiles() as $file) {
            $file->upload();
        }
    }

    public function getOntologieFile()
    {
        return $this->ontologyFiles;
    }
}
