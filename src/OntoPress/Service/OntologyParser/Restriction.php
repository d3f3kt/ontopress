<?php

namespace OntoPress\Service\OntologyParser;

/**
 * Class Restriction
 * @package OntoPress\Service\OntologyParser
 */
class Restriction
{
    /**
     * Array, that contains the possible choices for this property/restriction
     * 
     * @var array
     */
    protected $oneOf;

    /**
     * Restriction constructor.
     * 
     * @return Restriction this restriction-object
     */
    public function __construct()
    {
        $this->oneOf = array();
        return $this;
    }

    /**
     * Add a choice to $oneOf
     * 
     * @param OntologyNode $choice
     * @return Restriction $this
     */
    public function addOneOf($choice)
    {
        array_push($this->oneOf, $choice);
        return $this;
    }

    /**
     * Get choice-array
     * 
     * @return array $oneOf
     */
    public function getOneOf()
    {
        return $this->oneOf;
    }
}
