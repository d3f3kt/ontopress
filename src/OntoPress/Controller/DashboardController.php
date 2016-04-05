<?php

namespace OntoPress\Controller;

use OntoPress\Library\AbstractController;

/**
 * Dashboard Controller.
 */
class DashboardController extends AbstractController
{
    /**
     * Show dashboard.
     *
     * @return string rendered twig template
     */
    public function showDashboardAction()
    {
        $ontologyRepo = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');
        $formRepo = $this->getDoctrine()->getRepository('OntoPress\Entity\Form');

        $ontologyCount = $ontologyRepo->getCount();
        $formCount = $formRepo->getCount();

        $resTableBuildings = array(
            array('id' => 2, 'title' => 'Uni Campus'),
            array('id' => 3, 'title' => 'Oper Leipzig'),
        );

        $resTablePlaces = array(
            array('id' => 1, 'title' => 'Augustusplatz'),
        );

        $dashTableOnto = $ontologyRepo->getMostUsedOntologies();

        $dashTableForm = $formRepo->createQueryBuilder('p')
            ->setMaxResults(5)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/dashboard.html.twig', array(
                'dashTableForm' => $dashTableForm,
                'dashTableOnto' => $dashTableOnto,
                'resTablePlaces' => $resTablePlaces,
                'resTableBuildings' => $resTableBuildings,
                'ontologyCount' => $ontologyCount,
                'formCount' => $formCount,
        ));
    }
}
