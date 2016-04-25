<?php

namespace OntoPress\Controller;

use OntoPress\Form\Resource\Type\AddResourceType;
use OntoPress\Library\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Resource Controller.
 * The Resource Controller is creating the dynamic Page Content for the Resource view.
 * It connects to the Database and renders the specific twig template for the different views.
 */
class ResourceController extends AbstractController
{

    /**
     * Handle the add request of a new resource.
     *
     * @param Request $request HTTP Request
     *
     * @return string rendered twig template
     */
    public function showAddAction(Request $request)
    {
        $ontologies = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Ontology')
            ->findAll();

        $form = $this->createForm(new AddResourceType(), null, array(
            'cancel_link' => $this->generateRoute('ontopress_forms'),
            'doctrineManager' => $this->get('ontopress.doctrine_manager'),
            'ontologies' => $ontologies,
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $formData = $form->getData();
            $ontoFormId = $formData['Formular'];
            return $this->redirectToRoute(
                'ontopress_resourceAddDetails',
                array('formId' => $ontoFormId)
            );
        }
        return $this->render('resource/resourceAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Handle the add details of a new resource and save them in database.
     *
     * @param Request $request HTTP Request
     *
     * @return string rendered twig template
     */
    public function showAddDetailsAction(Request $request)
    {
        if ($formId = $request->get('formId')) {
            $formEntity = $this->getDoctrine()->getRepository('OntoPress\Entity\Form')
                ->findOneById($formId);
            if (!$formEntity) {
                return $this->render('form/formNotFound.html.twig', array(
                    'id' => $formId,
                ));
            }
        } else {
            return $this->redirectToRoute('ontopress_resource');
        }

        $form = $this->get('ontopress.form_creation')->create($formEntity)->add('submit', 'submit');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arc2Manager = $this->get('ontopress.arc2_manager');
            $arc2Manager->store($form->getData(), wp_get_current_user()->user_nicename);
            $this->addFlashMessage(
                'success',
                'Ressource erfolgreich gespeichert'
            );
            
            return $this->redirectToRoute('ontopress_resource');
        }

        $template = $formEntity->getTwigCode();

        return $this->render('resource/resourceAddDetails.html.twig', array(
            'twig_template' => $template,
            'form' => $form->createView(),
        ));
    }

    /**
     * Show all resources.
     *
     * @return string rendered twig template
     */
    public function showManagementAction()
    {
        $sparqlManager = $this->get('ontopress.sparql_manager');
        
        $resourceManageTable = $sparqlManager->getAllManageRows();
            
        return $this->render('resource/resourceManagement.html.twig', array(
            'resourceManageTable' => $resourceManageTable
        ));
    }
    
    /**
     * Handle the delete request of one or more resources.
     *
     * @return string rendered twig template
     */
    public function showDeleteAction()
    {
        return $this->render('resource/resourceDelete.html.twig', array());
    }
}
