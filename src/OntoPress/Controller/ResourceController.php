<?php

namespace OntoPress\Controller;

use OntoPress\Form\Resource\Type\AddResourceType;
use OntoPress\Library\AbstractController;
use OntoPress\Form\Resource\Type\AddResourceDetailType;
use OntoPress\Library\Modules\SaftModule;
use Symfony\Component\HttpFoundation\Request;
use Saft\Rdf\NamedNode;
use Saft\Rdf\NamedNodeImpl;
use Saft\Rdf\StatementImpl;
use Saft\Rdf\AnyPatternImpl;
use Saft\Rdf\ArrayStatementIteratorImpl;
use Saft\Rdf\LiteralImpl;
use Saft\Rdf\StatementIterator;
use Saft\Sparql\SparqlUtils;
use Saft\Sparql\Result\EmptyResultImpl;
use Saft\Sparql\Result\SetResultImpl;
use Saft\Sparql\Result\StatementSetResultImpl;
use Saft\Sparql\Result\ValueResultImpl;

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
            $arc2Manager->store($form->getData());
            $this->addFlashMessage(
                'success',
                'Ressource erfolgreich gespeichert'
            );
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
        $store = $this->get('saft.store');

        // test statement
        $anyStatement = new StatementImpl(
            new NamedNodeImpl('http://localhost/Place/Augustusplatz'),
            new NamedNodeImpl('http://localhost/author'),
            new NamedNodeImpl('http://localhost/k00ni')
        );
        $anyStatement2 = new StatementImpl(
            new NamedNodeImpl('http://localhost/Place/Uni'),
            new NamedNodeImpl('http://localhost/date'),
            new NamedNodeImpl('http://localhost/01.01.2016')
        );

        $store->addStatements($anyStatement);
        $store->addStatements($anyStatement2);

        $graph = new NamedNodeImpl('graph:hallo');
        $store->addStatements($anyStatement, $graph);

        dump($store);

        // select all subjects
        //             FROM <http://localhost/>
        $selectQuery = 'SELECT DISTINCT * WHERE { ?subject ?p ?o. }';

        // test query
        $queryResult = $store->query($selectQuery, array());

        // speichern des subjects
        $subject = $queryResult->getVariables();
        dump($subject);


        // foreach für jedes subject alle triple in dem das subject vorkommt raussuchen und
        // daraus object für tabelle holen
        
        $resourceManageTable = array(
            array('id' => 1, 'title' => $subject[0], 'author' => 'k00ni', 'date' => '20.Jan 2016')
        );

        /*
         array('id' => 1, 'title' => 'Augustusplatz', 'author' => 'k00ni', 'date' => '20.Jan 2016'),
         array('id' => 2, 'title' => 'Uni Campus', 'author' => 'k00ni', 'date' => '22.Jan 2016'),
         array('id' => 3, 'title' => 'Oper Leipzig', 'author' => 'd3f3ct', 'date' => '22.Jan 2016'),
         */
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
