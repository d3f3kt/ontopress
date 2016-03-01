<?php

namespace OntoPress\Form\Ontology\Model;

class AddOntology
{
    private $name;

    private $ontologyFile;


    /**
     * Get the value of Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     *
     * @param mixed name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of Ontology File
     *
     * @return mixed
     */
    public function getOntologyFile()
    {
        return $this->ontologyFile;
    }

    /**
     * Set the value of Ontology File
     *
     * @param mixed ontologyFile
     *
     * @return self
     */
    public function setOntologyFile($ontologyFile)
    {
        $this->ontologyFile = $ontologyFile;

        return $this;
    }

}
