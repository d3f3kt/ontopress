<?php

namespace OntoPress\Form\Resource\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * That is an example form.
 */
class AddResourceDetailType extends AbstractType
{
    /**
     * Creates a Form including all OntologyFields that can be selected and added to a Resource
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit', new SubmitCancelType(), array(
                'label' => 'Speichern',
                'attr' => array('class' => 'button button-primary'),
                'cancel_link' => $options['cancel_link'],
                'cancel_label' => $options['cancel_label'],
            ));            
    }


    /**
     * Sets the resolver to default
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired('cancel_link');
        $resolver->setDefaults(array(
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
        ));
    }

    /**
     * Getter Name
     *
     * @return string "networkMessage"
     */
    public function getName()
    {
        return 'addResourceDetailType';
    }
}
