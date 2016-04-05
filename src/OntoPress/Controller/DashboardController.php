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
        $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');

        $count_ontology = $repository->createQueryBuilder('p')
            ->select('count(p)')
            ->getQuery()
            ->getSingleScalarResult();

        $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Form');

        $count_form = $repository->createQueryBuilder('p')
            ->select('count(p)')
            ->getQuery()
            ->getSingleScalarResult();


        $resTableBuildings = array(
            array('id' => 2, 'title' => 'Uni Campus'),
            array('id' => 3, 'title' => 'Oper Leipzig'),
        );

        $resTablePlaces = array(
            array('id' => 1, 'title' => 'Augustusplatz'),
        );


        $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');

        $query = $repository->createQueryBuilder('p')
            ->setMaxResults(5)
            ->orderBy('count(p.ontologyForms)','DESC')
            ->getQuery();

        $dashTableOnto = $query->getResult();


        $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Form');
        
        $dashTableForm = $repository->createQueryBuilder('p')
            ->setMaxResults(5)
            ->orderBy('p.id','DESC')
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/dashboard.html.twig', array(
                'dashTableForm' => $dashTableForm,
                'dashTableOnto' => $dashTableOnto,
                'resTablePlaces' => $resTablePlaces,
                'resTableBuildings' => $resTableBuildings,
                'count_ontology' => $count_ontology,
                'count_form' => $count_form,
        ));
    }
}
