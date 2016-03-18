<?php

namespace OntoPress\Service\OntologyParser;

class OntologyNode
{
    //Das Subjekt
    protected $name;

    //Dessen Label
    protected $label;

    //Weitere Eigenschaften
    protected $type;

    //Missing Comment
    protected $mandatory;

    //Verbundene restriction
    protected $restriction;

    public function __construct($name, $label, $type, $mandatory, $oneOf)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->mandatory = $mandatory;
        $this->restriction = new Restriction(/*$mandatory,*/ $oneOf);
        return $this;
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

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
        return $this;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function setRestriction($oneOf)
    {
        $this->restriction = new Restriction($oneOf);//$restriction;
        return $this;
    }

    public function getRestriction()
    {
        return $this->restriction;
    }
}
