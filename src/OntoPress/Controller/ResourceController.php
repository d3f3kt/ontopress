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
use Saft\Sparql\Result\ValueResult;

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
        
        //get formId
        $id = $request->get('formId');
        //$id = 2;
        //fetch the form by formId
        /*$formEntity = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Form')
            ->find($id);
        */
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
       
        $rawForm = $this->get('ontopress.form_creation')->create($formEntity);
        
        $form = $this->createForm(new AddResourceDetailType(), null, array(
            'data' => $formEntity,
            'cancel_link' => $this->generateRoute('ontopress_resource'),
        ));

        return $this->render('resource/resourceAddDetails.html.twig', array(
            'form' => $rawForm->createView(),
            'form1' => $form->createView(),
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
        // mit foreach jedes triple im store durchgehen
        foreach ($store as $statement) {
            query = '
                SELECT ?subject
            '
            // im zweiten foreach den store nach triplen mit dem selben subjekt durchsuchen,
            // zu diesem subjekt dann alle objekte in tabelle eintragen
            foreach {

            }
        }*/

        ########################################################################

        /*$tableArray = array();
        $id = 0;

        // TODO: one huge loop, until all triples were read --> statement interator?
        $store = $this->get('saft.store');

        // ## TEST ## //
        $anyStatement = new StatementImpl(
            new NamedNodeImpl('http://localhost/Place/Augustusplatz'),
            new NamedNodeImpl('http://localhost/street_name'),
            new NamedNodeImpl('http://localhost/property/Teststraße7')
        );

        $store->addStatements($anyStatement); // TODO: ARC2 provideds statement iterator to loop through all triples?
        $query = '
            SELECT ?subject ?predicate ?object
            WHERE {
                ?subject ?predicate ?object
            }
        ';

        dump($anyStatement);
        $triple = $store->query($query, array());

        // TODO: Author und Date sind Platzhalter zum identifizieren
        switch ($triple[1]) {
            case 'Author':
                $predicate = 2;
                break;
            case 'Date':
                $predicate = 3;
                break;
            default:
                // Exception?
                break;
        }

        $newTitle = true;
        $n = 0;
        foreach ($tableArray as $array) {
            if ($triple[0] == $array[0]) {
                $tableArray[$n][$predicate] = $triple[2];
                $newTitle = false;
                break;
            }
            $n = $n+1;
        }
        if ($newTitle == true) {
            array_push($tableArray, array($id+1, $triple[0], '', ''));
            $tableArray[$id][$predicate] = $triple[2];
        }

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
