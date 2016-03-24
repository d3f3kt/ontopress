<?php

namespace OntoPress\Service\OntologyParser;

use Symfony\Component\Form\Extension\Core\DataMapper\RadioListMapper;

/**
 * OntologyNode.
 */
class OntologyNode
{
    /**
     * The name of the OntologyNode and the URI of the subject at the same time.
     *
     * @var Name
     */
    protected $name;

    /**
     * The label and a object of label-relation of the subject.
     *
     * @var Label
     */
    protected $label;

    /**
     * Object of comment-relation of the subject.
     *
     * @var Comment
     */
    protected $comment;

    /**
     * Type of the input.
     *
     * @var Type
     */
    protected $type;

    const TYPE_TEXT = "Text";

    const TYPE_RADIO= "Radio-Button";

    const TYPE_CHECK = "Checkbox";

    const TYPE_CHOICE = "Restriction-Choice";

    /**
     * Mandatory property.
     *
     * @var Mandatory
     */
    protected $mandatory;

    /**
     * Connected restriction.
     *
     * @var Restriction
     */
    protected $restriction;

    /**
     * Initialize OntologyNode.
     *
     * @param string  $name      The name of the OntologyNode
     * @param string  $label     The label of the subject
     * @param string  $comment   Object of comment-relation
     * @param string  $type      Type of the input
     * @param boolean $mandatory Mandatory Property
     * @param array   $oneOf     Array of Restrictions
     *
     * @return this object.
     */
    public function __construct($name, $label = null, $comment = null, $type = null, $mandatory = null, $oneOf = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->comment = $comment;
        $this->type = $type;
        $this->mandatory = $mandatory;
        $this->oneof = $oneOf;
        return $this;
    }

    /**
     * Set the name.
     *
     * @param string $name Name
     *
     * @return this object
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the name.
     *
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the label.
     *
     * @param string $label Label
     *
     * @return this object
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    
    /**
     * Get the label.
     *
     * @return string label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the comment.
     *
     * @param string $comment Comment
     *
     * @return this object
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
    
    /**
     * Get the comment.
     *
     * @return string comment
     */
    public function getComment()
    {
        return $this->comment;
    }
    
    /**
     * Set the type.
     *
     * @param string $type Type
     *
     * @return this object
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * Get the type.
     *
     * @return string type
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set the mandatory property.
     *
     * @param boolean $mandatory Mandatory
     *
     * @return this object
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
        return $this;
    }
    
    /**
     * Get mandatory.
     *
     * @return boolean mandatory
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }
    
    /**
     * Set the restriction.
     *
     * @param Restriction $restriction Restriction
     *
     * @return this object
     */
    public function setRestriction($oneOf)
    {
        $this->restriction = $oneOf;
        return $this;
    }
    
    /**
     * Get the restriction.
     *
     * @return Restriction restriction
     */
    public function getRestriction()
    {
        return $this->restriction;
    }
}
