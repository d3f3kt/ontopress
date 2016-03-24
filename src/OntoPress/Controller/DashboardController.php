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
        $resTableBuildings = array(
            array('id' => 2, 'title' => 'Uni Campus'),
            array('id' => 3, 'title' => 'Oper Leipzig'),
        );

        $resTablePlaces = array(
            array('id' => 1, 'title' => 'Augustusplatz'),
        );

        $dashTableOnto = array(
            array('id' => 1, 'name' => 'Gebäude', 'form' => 10, 'resource' => 2),
            array('id' => 2, 'name' => 'Plätze', 'form' => 5, 'resource' => 1),
            array('id' => 3, 'name' => 'Kirchen', 'form' => 8, 'resource' => 0),
        );

        $dashTableForm = array(
            array('id' => 1, 'ontology' => 'Gebäude', 'formName' => 'Schulen'),
            array('id' => 2, 'ontology' => 'Plätze', 'formName' => 'öffentliche Plätze'),
            array('id' => 3, 'ontology' => 'Kirchen', 'formName' => 'öffentliche Plätze'),
        );

        return $this->render('dashboard/dashboard.html.twig', array(
                'dashTableForm' => $dashTableForm,
                'dashTableOnto' => $dashTableOnto,
                'resTablePlaces' => $resTablePlaces,
                'resTableBuildings' => $resTableBuildings,
        ));
    }
}
