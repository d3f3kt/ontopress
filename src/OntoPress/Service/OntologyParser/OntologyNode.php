<?php

namespace OntoPress\Service\OntologyParser;

class OntologyNode
{
    //URI of subject
    protected $name;

    //label of this subject
    protected $label;

    //object of comment-relation
    protected $comment;

    //further objects
    protected $type;

    const TYPE_TEXT = "Text";

    const TYPE_RADIO= "Radio-Button";

    const TYPE_CHECK = "Checkbox";

    const TYPE_CHOICE = "Restriction-Choice";

    //Missing Comment
    protected $mandatory;

    //Connected restriction
    protected $restriction;

    public function __construct($name, $label, $comment, $type, $mandatory, $oneOf)
    {
        $this->name = $name;
        $this->label = $label;
        $this->comment = $comment;
        $this->type = $type;
        $this->mandatory = $mandatory;
        $this->oneof = $oneOf;
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

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
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
        $this->restriction = $oneOf;
        return $this;
    }

    public function getRestriction()
    {
        return $this->restriction;
    }
}
