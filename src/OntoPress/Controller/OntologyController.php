<?php

namespace OntoPress\Controller;

use OntoPress\Form\Ontology\Type\AddOntologyForm;
use OntoPress\Form\Ontology\Model\AddOntology;
use OntoPress\Libary\AbstractController;

class OntologyController extends AbstractController
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
        $form = $this->createForm(new AddOntologyForm(), $ontology, array(
            'cancel_link' => $this->generateRoute('ontopress_ontology'),
        ));

        return $this->render('ontology/ontologyAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
