<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DataOntology
 *
 * @ORM\Table(name="ontopress_dataOntology")
 * @ORM\Entity()
 */
class DataOntology
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Assert\NotBlank()
     */
    protected $id;

    /**
     * @var OntologyField
     * @ORM\OneToMany(targetEntity="OntologyField", mappedBy="dataOntology", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $ontologyFields;

    /**
     * @var Ontology
     * @ORM\ManyToOne(targetEntity="Ontology", inversedBy="dataOntologies")
     */
    protected $ontology;


    public function __construct()
    {
        $this->ontologyFields = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
