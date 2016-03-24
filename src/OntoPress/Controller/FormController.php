<?php
namespace OntoPress\Controller;

use OntoPress\Library\AbstractController;
use OntoPress\Form\Form\Type\EditFormType;

/**
 * Form Controller.
 */
class FormController extends AbstractController
{
    /**
     * Show all forms.
     *
     * @return string rendered twig template
     */
    public function showManageAction()
    {
        $formManageTable = array(
            array('id' => 1, 'name' => 'öffentliche Plätze', 'author' => 'k00ni', 'date' => '20.Jan 2016'),
            array('id' => 2, 'name' => 'Schulen', 'author' => 'k00ni', 'date' => '20.Jan 2016'),
            array('id' => 3, 'name' => 'Galerie', 'author' => 'd3f3ct', 'date' => '20.Jan 2016'),
        );

        return $this->render('form/manageForms.html.twig', array(
            'formManageTable' => $formManageTable
        ));
    }

    /**
     * Handle the edit request of a form.
     *
     * @return string rendered twig template
     */
    public function showEditAction()
    {
        $form = $this->createForm(new EditFormType(), null, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
        ));

        return $this->render('form/formEdit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Handle the add request of a form.
     *
     * @return string rendered twig template
     */
    public function showCreateAction()
    {
        $form = $this->createForm(new EditFormType(), null, array(
        'cancel_link' => $this->generateRoute('ontopress_forms'),
        ));

        return $this->render('form/formCreate.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Handle the delete request of a form.
     *
     * @return string rendered twig template
     */
    public function showDeleteAction()
    {
        return $this->render('form/formDelete.html.twig', array());
    }
}
