<?php
namespace OntoPress\Controller;

use OntoPress\Libary\Controller;

class OntologyController extends Controller
{
    public function showManageAction()
    {
        return $this->render('ontology/managePage.html.twig', array());
    }

    public function showDeleteAction()
    {
        return $this->render('ontology/delete.html.twig', array());
    }

    public function showAddAction()
    {
        return $this->render('ontology/ontologyAdd.html.twig', array());
    }
}
