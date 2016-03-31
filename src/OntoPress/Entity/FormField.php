<?php

namespace OntoPress\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form Field.
 *
 * @ORM\Table(name="ontopress_form_field")
 * @ORM\Entity()
 */
class FormField
{
    /**
     * Primary Key.
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Stream
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="formFields")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $form;

    /**
     * fieldUri.
     * ID of the subject/OntologyNode, that is represented by this field
     * e.g. http://localhost/aksw/knorke/Restriction
     *
     * @var string
     * @ORM\Column(name="field_uri", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $fieldUri;

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
     * Set form.
     *
     * @param \OntoPress\Entity\Form $form
     *
     * @return FormField
     */
    public function setForm(\OntoPress\Entity\Form $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form.
     *
     * @return \OntoPress\Entity\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set fieldUri.
     *
     * @param string $fieldUri
     *
     * @return FormField
     */
    public function setFieldUri($fieldUri)
    {
        $this->fieldUri = $fieldUri;

        return $this;
    }

    /**
     * Get fieldUri.
     *
     * @return string
     */
    public function getFieldUri()
    {
        return $this->fieldUri;
    }
}
