<?php

namespace OntoPress\Controller;

use OntoPress\Form\Form\Type\SelectFormType;
use Symfony\Component\HttpFoundation\Request;
use OntoPress\Library\AbstractController;
use OntoPress\Entity\Form;
use OntoPress\Entity\FormField;
use OntoPress\Form\Form\Type\EditFormType;
use OntoPress\Form\Form\Type\CreateFormType;

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
            $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Form');
            $formManageTable = $repository->findAll();
        } else {
            $formManageTable = $ontology->getOntologyForm();
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
    public function showCreateAction(Request $request)
    {
        if ($request->get('ontologyId')) {
            return $this->showCreateFormAction($request);
        } else {
            return $this->showSelectOntologyAction($request);
        }
    }

    public function showSelectOntologyAction(Request $request)
    {
        $form = $this->createForm(new CreateFormType(), null, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
            'doctrineManager' => $this->get('ontopress.doctrine_manager'),
        ));
        $form->handleRequest($request);

        if($form->isValid()){
            $formData = $form->getData();
            $ontology = $formData['ontology'];
            return $this->redirectToRoute(
                'ontopress_formsCreate',
                array('ontologyId' => $ontology->getId())
            );
        }

        return $this->render('form/formCreate.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showCreateFormAction(Request $request)
    {
        $author = wp_get_current_user();

        $ontoForm = new Form();
        $ontoForm->setAuthor($author->user_nicename)
            ->setDate(new \DateTime())
            ->setTwigCode('REPLACE_ME')
            ->addFormField(new FormField());

        $form = $this->createForm(new SelectFormType(), null, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
            'doctrineManager' => $this->get('ontopress.doctrine_manager'),
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            /*
            $this->getDoctrine()->persist($ontForm);
            $this->getDoctrine()->flush();

            $this->addFlashMessage(
                'success',
                'Formular, erflogreich erstellt.'
            );
            return $this->redirectToRoute('ontopress_forms');
            */
        }
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
            return $this->render('ontology/notFound.html.twig', array(
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


            return $this->redirectToRoute('ontopress_ontology');
        }

        return $this->render('form/formDelete.html.twig', array(
            'ontologyDelete' => $formDelete,
            'form' => $form->createView(),
        ));
    }
}
