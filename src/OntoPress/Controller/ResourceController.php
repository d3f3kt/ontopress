<?php

namespace OntoPress\Controller;

use OntoPress\Form\Resource\Type\AddResourceType;
use OntoPress\Library\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use OntoPress\Form\Resource\Type\DeleteResourceType;

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
            $arc2Manager->store($form->getData(), $formId, wp_get_current_user()->user_nicename);
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
     * Show all resources of the whole Store or all resources of a specific Ontology.
     *
     * @return string rendered twig template
     */
    public function showManagementAction(Request $request)
    {
        $graph = $request->get('graph');
        $sparqlManager = $this->get('ontopress.sparql_manager');
        $ontologies = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology')->findAll();
        $graphs = array();
        
        foreach ($ontologies as $ontology) {
            $graphs[] = array(
                'name' => $ontology->getName(),
            );
        }
        
        if (!empty($request->get('s'))) {
            if (!$request->get('graph')) {
                $resourceManageTable = $this->getSearchTable($request->get('s'));
            } else {
                $resourceManageTable = $this->getSearchTable($request->get('s'), $graph);
            }
        } else {
            if ((empty($graph))) {
                $resourceManageTable = $sparqlManager->getAllManageRows();
            } else {
                $graph = 'graph:' . $graph;
                $resourceManageTable = $sparqlManager->getAllManageRows($graph);
            }
        }
        
        if (!empty($orderBy = $request->get('orderBy'))) {
            $resourceManageTable = $sparqlManager->getSortedTable($orderBy, $request->get('order'));
        }
        
        return $this->render('resource/resourceManagement.html.twig', array(
            'resourceManageTable' => $resourceManageTable,
            'graphs' => $graphs,
        ));
        
    }
    
    /**
     * Handle the delete request of one or more resources.
     *
     * @return string rendered twig template
     */
    public function showDeleteAction(Request $request)
    {
        $resource = $request->get('uri');
        //delete not edit
        $form = $this->createForm(new DeleteResourceType(), null, array(
            'cancel_link' => $this->generateRoute('ontopress_resource'),
        ));

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->get('ontopress.sparql_manager')->deleteResource($resource);
            $this->addFlashMessage(
                'success',
                'Ressource erfolgreich gelöscht.'
            );
            
            return $this->redirectToRoute('ontopress_resource');
        }
        return $this->render('resource/resourceDelete.html.twig', array(
            'title' => $request->get('title'),
            'form' => $form->createView()
        ));
    }

    /**
     * Handle editing of a resource
     *
     * @param  Request  $request
     * @return string   rendered twig template
     */
    public function showEditAction(Request $request)
    {
        if ($resourceUri = $request->get('uri')) {
            $formId = $this->get('ontopress.sparql_manager')->getFormId($resourceUri);
            $resForm = $this->getDoctrine()->getRepository('OntoPress\Entity\Form')
                ->findOneById($formId);
            if (!$resForm) {
                $this->addFlashMessage(
                    'fail',
                    'Ressource konnte nicht geladen werden (zugehöriges Formular fehlt)'
                );

                return $this->redirectToRoute('ontopress_resource');
            }
        } else {
            return $this->redirectToRoute('ontopress_resource');
        }
    
        $formValues = $this->get('ontopress.sparql_manager')->getResourceTriples($resourceUri);

        $form = $this->get('ontopress.form_creation')->createFilledForm($resForm, $formValues)->add('submit', 'submit');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('ontopress.sparql_manager')->deleteResource($resourceUri);
            $this->get('ontopress.arc2_manager')->store(
                $form->getData(),
                $formId,
                wp_get_current_user()->user_nicename
            );

            $this->addFlashMessage(
                'success',
                'Ressource erfolgreich bearbeitet'
            );

            return $this->redirectToRoute('ontopress_resource');
        }

        return $this->render('resource/resourceEdit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function getSearchTable($searchString, $graph = null)
    {
        $sparqlManager = $this->get('ontopress.sparql_manager');
        if (!$graph) {
            $resourceManageTable = $sparqlManager->getResourceRowsLike($searchString);
        } else {
            $graph = 'graph:'. $graph;
            $resourceManageTable = $sparqlManager->getResourceRowsLike($searchString, $graph);
        }

        return $resourceManageTable;
    }
}
