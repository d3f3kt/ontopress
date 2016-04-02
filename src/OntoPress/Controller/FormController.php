<?php

namespace OntoPress\Controller;

use Symfony\Component\HttpFoundation\Request;
use OntoPress\Library\AbstractController;
use OntoPress\Entity\Form;
use OntoPress\Form\Form\Type\EditFormType;
use OntoPress\Form\Form\Type\CreateFormType;
use OntoPress\Form\Form\Type\DeleteFormType;

/**
 * Form Controller.
 */
class FormController extends AbstractController
{
    /**
     * Show all forms.
     *
     * @param Request $request HTTP Request
     *
     * @return string rendered twig template
     */
    public function showManageAction(Request $request)
    {
        $id = $request->get('id', 0);

        $ontology = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Ontology')
            ->find($id);

        if (!$ontology) {
            $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');
            $formManageTable = $repository->findAll();
        } else {
            $formManageTable = $ontology->getOntologyForms();
        }

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
     * @param Request $request HTTP Request
     *
     * @return string rendered twig template
     */
    public function showDeleteAction(Request $request)
    {
        $id = $request->get('id', 0);

        $formDelete = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Form')
            ->find($id);

        if (!$formDelete) {
            return $this->render('form/formNotFound.html.twig', array(
                'id' => $id,
            ));
        }

        $form = $this->createForm(new DeleteFormType(), $formDelete, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
        ));

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->getDoctrine()->remove($formDelete);
            $this->getDoctrine()->flush();
            $this->addFlashMessage(
                'success',
                'Formular erfolgreich gelöscht.'
            );


            return $this->redirectToRoute('ontopress_forms');
        }

        return $this->render('form/formDelete.html.twig', array(
            'ontologyDelete' => $formDelete,
            'form' => $form->createView(),
        ));
    }
}
