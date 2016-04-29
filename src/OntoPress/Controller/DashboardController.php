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

        $ontologyCount = $ontologyRepo->getCount();
        $formCount = $formRepo->getCount();

        $sparqlManager = $this->get('ontopress.sparql_manager');
        $resourceCount = $sparqlManager->countResources();

        $resTableBuildings = $sparqlManager->getLatestResources('graph:onto');
        $resTableBuildings = array_slice($resTableBuildings, 0, 5);
        $resTablePlaces = $sparqlManager->getLatestResources('graph:Plätze');
        $resTablePlaces = array_slice($resTablePlaces, 0, 5);
        $resTableChurches = $sparqlManager->getLatestResources('graph:Kirchen');
        $resTableChurches = array_slice($resTableChurches, 0, 5);
      /*  $resTableBuildings = array(
            array('id' => 2, 'title' => 'Uni Campus'),
            array('id' => 3, 'title' => 'Oper Leipzig'),
        );

        $resTablePlaces = array(
            array('id' => 1, 'title' => 'Augustusplatz'),
        );*/

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
                'resTablePlaces' => $resTablePlaces,
                'resTableChurches' => $resTableChurches,
                'resTableBuildings' => $resTableBuildings,
                'ontologyCount' => $ontologyCount,
                'formCount' => $formCount,
                'resourceCount' => $resourceCount,
        ));
    }
}
