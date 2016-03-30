<?php
namespace OntoPress\Controller;

use OntoPress\Library\AbstractController;
use OntoPress\Entity\Form;
use OntoPress\Form\Form\Type\EditFormType;
use OntoPress\Form\Form\Type\CreateFormType;
use Symfony\Component\DependencyInjection\Container;
use OntoPress\Entity\Form;

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
        $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Form');
        $formManageTable = $repository->findAll();


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
        /*
        $author = wp_get_current_user();
        $ontoForm = new Form();

        $ontoForm->setAuthor($author->user_nicename)
            ->setDate(new \DateTime());
         */

        $form = $this->createForm(new CreateFormType(), null, array(
        'cancel_link' => $this->generateRoute('ontopress_forms'),
	    'doctrineManager' => $this->get('ontopress.doctrine_manager'),
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
