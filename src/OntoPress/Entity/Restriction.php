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
     * @var OntologyField
     * @ORM\ManyToOne(targetEntity="OntologyField", inversedBy="restriction")
     * @ORM\JoinColumn(name="ontologyField_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $ontologyField;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=256)
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

    /**
     * Set ontologyField
     *
     * @param \OntoPress\Entity\OntologyField $ontologyField
     *
     * @return Restriction
     */
    public function setOntologyField(\OntoPress\Entity\OntologyField $ontologyField = null)
    {
        $this->ontologyField = $ontologyField;

        return $this;
    }

    /**
     * Get ontologyField
     *
     * @return \OntoPress\Entity\OntologyField
     */
    public function getOntologyField()
    {
        return $this->ontologyField;
    }
}
