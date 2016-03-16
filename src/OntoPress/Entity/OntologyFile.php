<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ontology File.
 *
 * @ORM\Table(name="wp_ontology_file")
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
     * Get id.
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
     * Set ontology.
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
     *
     * @return \OntoPress\Entity\Ontology
     */
    public function getOntology()
    {
        return $this->ontology;
    }
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

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

        $this->path = $newFileName;
    }

    public function getAbsolutePath()
    {
        return $this->path === null
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../Resources/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'ontology/upload';
    }
}
