<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OntologyNode
 *
 * @ORM\Table(name="ontopress_ontologyNode")
 * @ORM\Entity()
 */
class OntologyNode
{
    /**
     * @var OntologyFile
     * @ORM\ManyToOne(targetEntity="OntologyFile", inversedBy="ontologyNodes")
     */
    protected $ontologyFile;

    /**
     * @var string
     * @Assert\NotBlank()
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
     */
    protected $label;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var boolean
     */
    protected $mandatory;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Restriction
     */
    protected $restrictions;

    public function __construct()
    {
        $this->restrictions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function removeRestriction(\OntoPress\Entity\Restriction $restriction)
    {
        return $this->restrictions->removeElement($restriction);
    }

    public function addRestriction(\OntoPress\Entity\Restriction $newRestriction)
    {
        $newRestriction->setOntologyNode($this);
        $this->restrictions[] = $newRestriction;
    }
}
