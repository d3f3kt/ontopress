<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ontology File.
 * This PHP Object allows Doctrine to translate php Objects into an relational SQL Table via Metadata.
 *
 * @ORM\Table(name="ontopress_ontology_file")
 * @ORM\Entity()
 */
class OntologyFile
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Stream
     * @ORM\ManyToOne(targetEntity="Ontology", inversedBy="ontologyFiles")
     * @ORM\JoinColumn(name="ontology_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $ontology;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(mimeTypes={"text/turtle", "text/plain"})
     */
    private $file;

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
     * Set path.
     *
     * @param string $path
     *
     * @return OntologyFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Setter ontology.
     * Sets the Ontology for the many to one connection to Ontologys.
     * Default NULL.
     *
     * @param \OntoPress\Entity\Ontology $ontology
     *
     * @return OntologyFile
     */
    public function setOntology(\OntoPress\Entity\Ontology $ontology = null)
    {
        $this->ontology = $ontology;

        return $this;
    }

    /**
     * Get ontology.
     * Returns the Ontology, that this Object is related to
     *
     * @return \OntoPress\Entity\Ontology
     */
    public function getOntology()
    {
        return $this->ontology;
    }
    /**
     * Setter file.
     * Default NULL.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Getter file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Move the existing File to '/Resources/ontology/upload/$timestamp$authorname',
     * and set the existing Path to the new Path, where the File has been moved to.
     */
    public function upload()
    {
        if ($this->getFile() === null) {
            return;
        }

        $newFileName = time().$this->getFile()->getClientOriginalName();
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $newFileName
        );

        $this->setPath($newFileName);
    }

    /**
     * Get the absolute Path if it exists.
     * The Path looks like '/Resources/ontology/upload/$path'
     *
     * @return null|string AbsolutePath
     */
    public function getAbsolutePath()
    {
        return $this->path === null
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * Get the Upload direction including the Root
     *
     * @return string Upload root direction
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../Resources/'.$this->getUploadDir();
    }

    /**
     * Get the Upload direction, which looks like 'ontology/upload'.
     *
     * @return string Upload direction
     */
    protected function getUploadDir()
    {
        return 'ontology/upload';
    }
}
