<?php
namespace OntoPress\Controller;

use OntoPress\Libary\AbstractController;
use OntoPress\Form\Form\Type\EditFormType;

class FormController extends AbstractController
{
    public function showManageAction()
    {
        return $this->render('form/manageForms.html.twig', array());
    }

    public function showEditAction()
    {
        $form = $this->createForm(new EditFormType(), null, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
        ));

        return $this->render('form/formEdit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showCreateAction()
    {
        $form = $this->createForm(new EditFormType(), null, array(
        'cancel_link' => $this->generateRoute('ontopress_forms'),
        ));

        return $this->render('form/formCreate.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showDeleteAction()
    {
        return $this->render('form/formDelete.html.twig', array());
    }
}
