<?php
namespace OntoPress\Controller;

use OntoPress\Libary\Controller;

class OntologyController extends Controller
{
    public function showManageAction()
    {
        return $this->render('ontology/managePage.html.twig', array());
    }
}
