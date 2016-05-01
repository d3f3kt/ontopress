<?php

namespace OntoPress\Controller;

use OntoPress\Library\AbstractController;

/**
 * Dashboard Controller.
 * The Dashboard Controller is creating the dynamic Page Content for the Dashboard view.
 * It fetches the specific Data from the Database and renders a twig template.
 */
class DashboardController extends AbstractController
{
    /**
     * Show Dashboard.
     * Creates the dynamic Table content for the Dashboard view.
     * It fetches the Data out of the Database and determines for example
     * the 4 most used Ontologies and renders the twig template.
     *
     * @return string rendered twig template
     */
    public function showDashboardAction()
    {
        $ontologyRepo = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');
        $formRepo = $this->getDoctrine()->getRepository('OntoPress\Entity\Form');
        $sparqlManager = $this->get('ontopress.sparql_manager');

        $ontologyCount = $ontologyRepo->getCount();
        $formCount = $formRepo->getCount();
        $resourceCount = $sparqlManager->countResources();

        $ontologies = $ontologyRepo->findAll();
        $resourceTables =array();

        foreach ($ontologies as $ontology) {
            $graph = 'graph:'. $ontology->getName();
            $resources = $sparqlManager->getLatestResources($graph);
            $resources = array_slice($resources, 0, 5);
            array_push($resourceTables, array('ontologyName' => $ontology->getName(), 'tableContent' => $resources));
        }

        $mostUsedOntologys = $ontologyRepo->getMostUsedOntologies();
        $dashTableOnto = array();
        foreach ($mostUsedOntologys as $onto) {
            array_push($dashTableOnto, array('id' => $onto->getId(), 'name' => $onto->getName(),
                'ontologyForms' => $onto->getOntologyForms(), 'resource' => $sparqlManager->countResources('graph:'. $onto->getName())));
        }

        $dashTableForm = $formRepo->createQueryBuilder('p')
            ->setMaxResults(5)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/dashboard.html.twig', array(
                'dashTableForm' => $dashTableForm,
                'dashTableOnto' => $dashTableOnto,
                'resourceTables' => $resourceTables,
                'ontologyCount' => $ontologyCount,
                'formCount' => $formCount,
                'resourceCount' => $resourceCount,
        ));
    }
}
