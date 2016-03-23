<?php

namespace OntoPress\Service\OntologyParser;

class Restriction
{
    //Possible answers
    protected $oneOf;

    public function __construct()
    {
        $this->oneOf = array();
        return $this;
    }

    public function addOneOf($choice)
    {
        array_push($this->oneOf, $choice);
        return $this;
    }

    public function getOneOf()
    {
        return $this->oneOf;
    }
}
