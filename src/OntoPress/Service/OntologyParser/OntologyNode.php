<?php

namespace OntoPress\Service\OntologyParser;

class OntologyNode
{
    //Das Subjekt?
    protected $name;

    //Das Objekt?
    protected $label;

    // Text, Kommentar oder Buttons etc.
    protected $type;

    protected $restriction;

    public function __construct()
    {

    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
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

    public function setRestriction($restriction)
    {
        $this->restriction = $restriction;
        return $this;
    }

    public function getRestrcition()
    {
        return $this->restriction;
    }
}
