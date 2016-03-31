<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Restriction.
 *
 * @ORM\Table(name="ontopress_restriction")
 * @ORM\Entity()
 */
class Restriction
{
    /**
     * Primary Key
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var OntologyNode
     * @ORM\ManyToOne(targetEntity="OntologyNode", inversedBy="restriction")
     * @ORM\JoinColumn(name="ontologyNode_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $ontologyNode;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=32)
     * @Assert\NotBlank()
     */
    protected $name;
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
     * Set ontologyNode.
     *
     * @param OntologyNode $ontologyNode
     *
     * @return Restriction
     */
    public function setOntologyNode(\OntoPress\Entity\OntologyNode $ontologyNode = null)
    {
        $this->ontologyNode = $ontologyNode;
        return $this;
    }
    /**
     * Get ontologyNode.
     *
     * @return OntologyNode
     */
    public function getOntologyNode()
    {
        return $this->ontologyNode;
    }
    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Restriction
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
}
