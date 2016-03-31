<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ontology File.
 *
 * @ORM\Table(name="ontopress_ontology_file")
 * @ORM\Entity()
 */
class OntologyFile
{
    /**
     * @var OntologyNode
     * @ORM\OneToMany(targetEntity="OntologyNode", mappedBy="ontologyFile", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $ontologyNodes;

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

    public function __construct()
    {
        $this->ontologyNodes = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function addOntologyNode(\OntoPress\Entity\OntologyNode $newOntologyNode)
    {
        $newOntologyNode->setOntology($this);
        $this->ontologyNodes[] = $newOntologyNode;

        return $this;
    }

    public function removeOntologyNode(\OntoPress\Entity\OntologyNode $ontologyNode)
    {
        return $this->ontologyNodes->removeElement($ontologyNode);
    }

    /**
     * Get ontologyNodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOntologyNodes()
    {
        return $this->ontologyNodes;
    }
}
