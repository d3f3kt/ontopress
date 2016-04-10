<?php

namespace OntoPress\Controller;

use OntoPress\Form\Form\Type\SelectFormType;
use Symfony\Component\HttpFoundation\Request;
use OntoPress\Library\AbstractController;
use OntoPress\Entity\Form;
use OntoPress\Form\Form\Type\EditFormType;
use OntoPress\Form\Form\Type\CreateFormType;
use OntoPress\Form\Form\Type\DeleteFormType;

/**
 * Form Controller.
 * The Form Controller is creating the dynamic Page Content for the Form view.
 * It connects to the Database and renders the specific twig template for the different views.
 */
class FormController extends AbstractController
{
    /**
     * Show all forms.
     * Creates the dynamic Table content for the Form manage view.
     * It fetches all Forms from the Database and and renders the twig template.
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
            $formManageTable = $this->getDoctrine()
                ->getRepository('OntoPress\Entity\Form')
                ->findAll();
        } else {
            $formManageTable = $ontology->getOntologyForms();
        }

        return $this->render('form/manageForms.html.twig', array(
            'formManageTable' => $formManageTable,
        ));
    }

    /**
     * Handle the edit request of a form.
     *
     * @return string rendered twig template
     */
    public function showEditAction(Request $request)
    {
        if ($formId = $request->get('formId')) {
            $ontoForm = $this->getDoctrine()->getRepository('OntoPress\Entity\Form')
                ->findOneById($formId);
            if (!$ontoForm) {
                throw new Exception('No form found');
            }
        }

        $form = $this->createForm(new EditFormType(), $ontoForm, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
        ));

        $symForm = $this->get('ontopress.form_creation')->create($ontoForm);
        $twigTemplate = $this->get('twig')->createTemplate('{{ form(form) }}');
        print($twigTemplate->render(array('form' => $symForm->createView())));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->persist($ontoForm);
            $this->getDoctrine()->flush();
            
            $this->addFlashMessage(
                'success',
                'Formular erfolgreich bearbeitet'
            );

            return $this->redirectToRoute('ontopress_forms');
        }

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

        if ($form->isValid()) {
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

        if ($ontoId = $request->get('ontologyId')) {
            $ontoRepo = $this->getDoctrine()
                ->getRepository('OntoPress\Entity\Ontology');

            if (!$ontology = $ontoRepo->findOneById($ontoId)) {
                throw new Exception('No Ontology found');
            }
        }

        $ontoForm = new Form();
        $ontoForm->setAuthor($author->user_nicename)
            ->setDate(new \DateTime())
            ->setTwigCode('REPLACE_ME')
            ->setOntology($ontology);

        $form = $this->createForm(new SelectFormType(), $ontoForm, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
            'doctrineManager' => $this->get('ontopress.doctrine_manager'),
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $ontoForm->setTwigCode(
                $this->get('ontopress.twig_generator')->generate($ontoForm)
            );

            $this->getDoctrine()->persist($ontoForm);
            $this->getDoctrine()->flush();

            $this->addFlashMessage(
                'success',
                'Formular, erflogreich erstellt.'
            );

            return $this->redirectToRoute(
                'ontopress_formsEdit',
                array('formId' => $ontoForm->getId())
            );
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
            'formDelete' => $formDelete,
            'form' => $form->createView(),
        ));
    }
}
