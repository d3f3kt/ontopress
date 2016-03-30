<?php

namespace OntoPress\Controller;

use Symfony\Component\HttpFoundation\Request;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\Form;
use OntoPress\Entity\OntologyFile;
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


        $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');

        $query = $repository->createQueryBuilder('p')
            ->where('p.id < :id')
            ->setParameter('id', '4')
            ->orderBy('p.id', 'ASC')
            ->getQuery();

        $dashTableOnto = $query->getResult();






        //array('id' => 2, 'name' => 'Plätze', 'form' => 5, 'resource' => 1),
        //array('id' => 3, 'name' => 'Kirchen', 'form' => 8, 'resource' => 0)
        // );


       /* $dashTableForm = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Form')
            ->findAll(); */




        $dashTableForm= array(
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
