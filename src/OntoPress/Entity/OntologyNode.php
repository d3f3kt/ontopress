<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Colum(type="integer")
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

    //////////////////////
    //      Getter      //
    /////////////////////

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOntologyFiles()
    {
        return $this->ontologyFile;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRestrictions()
    {
        return $this->restriction;
    }

    //////////////////////
    //      Setter      //
    /////////////////////

    public function setLabel($newLabel)
    {
        $this->label = $newLabel;

        return $this;
    }

    public function setName($newName)
    {
        $this->name = $newName;

        return $this;
    }

    public function setComment($newComment)
    {
        $this->comment = $newComment;

        return $this;
    }

    public function setMandatory($bool)
    {
        $this->mandatory = $bool;

        return $this;
    }

    public function setType($newType)
    {
        $this->type = $newType;

        return $this;
    }

    public function setRestriction(\OntoPress\Entity\Restriction $newRestrictionObj = null)
    {
        $this->restriction = $newRestrictionObj;

        return $this;
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
