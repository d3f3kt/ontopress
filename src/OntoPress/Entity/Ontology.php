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
     * File path of ontology.
     *
     * @var string
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    protected $ontologyFile;

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
     * Set ontologyFile.
     *
     * @param string $ontologyFile
     *
     * @return Ontology
     */
    public function setOntologyFile($ontologyFile)
    {
        $this->ontologyFile = $ontologyFile;

        return $this;
    }

    /**
     * Get ontologyFile.
     *
     * @return string
     */
    public function getOntologyFile()
    {
        return $this->ontologyFile;
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
    public function setDate($date)
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
}
