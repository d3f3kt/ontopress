<?php

namespace OntoPress\Service\OntologyParser;

class Restriction
{
    //Pflichtfeld? (true/false)
    protected $mandatory;

    //Antwortmoeglichkeiten
    protected $oneOf;


    public function __construct($mandatory, $oneOf)
    {
        $this->mandatory = $mandatory;
        $this->oneOf = $oneOf;
        return $this;
    }

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
        return $this;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function setOneOf($oneOf)
    {
        $this->oneOf = $oneOf;
        return $this;
    }

    public function getOneOf()
    {
        return $this->oneOf;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
}
