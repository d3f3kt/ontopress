<?php

namespace OntoPress\Controller;

use OntoPress\Form\Ontology\Type\AddOntologyForm;
use OntoPress\Form\Ontology\Model\AddOntology;
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
        $ontology = new AddOntology();
        $form = $this->createForm(new AddOntologyForm(), $ontology);

        return $this->render('ontology/ontologyAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
