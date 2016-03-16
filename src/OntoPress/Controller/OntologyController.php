<?php

namespace OntoPress\Controller;

use OntoPress\Form\Ontology\Type\AddOntologyType;
use OntoPress\Entity\Ontology;
use OntoPress\Libary\AbstractController;

class OntologyController extends AbstractController
{
    public function showManageAction()
    {
        $ontologyManageTable = array(
            array('id' => 1, 'ontology' => 'Gebäude', 'form' => 10),
            array('id' => 2, 'ontology' => 'Plätze', 'form' => 5),
            array('id' => 3, 'ontology' => 'Kirchen', 'form' => 8),

        );

        return $this->render('ontology/managePage.html.twig', array(
            'ontologyManageTable' => $ontologyManageTable
        ));
    }

    public function showDeleteAction()
    {
        return $this->render('ontology/delete.html.twig', array());
    }

    public function showAddAction()
    {
        $ontology = new Ontology();
        $form = $this->createForm(new AddOntologyType(), $ontology, array(
            'cancel_link' => $this->generateRoute('ontopress_ontology'),
        ));

        $form->handleRequest();
        
        if ($form->isValid()) {
            $ontologyName = substr($ontology->getName(), 0, 3);
            $timestamp = time();
            $author = wp_get_current_user();
            $date = date('j F Y');
            $path = 'Resources/ontology/'.(string)$ontologyName.'_'.(string)$timestamp;
            $ontology->setOntologyFile($path);
            $ontology->setAuthor($author->user_nicename);
            $ontology->setDate($date);
            $this->getDoctrine()->persist($ontology);
            $this->getDoctrine()->flush();

          /*  // move takes the target directory and then the
            // target filename to move to
            $this->getFile()->move(
                $this->getUploadRootDir(),
                $this->getFile()->getClientOriginalName()
            );

            // set the path property to the filename where you've saved the file
            $this->path = $this->getFile()->getClientOriginalName();

            // clean up the file property as you won't need it anymore
            $this->file = null; */
        }

        return $this->render('ontology/ontologyAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
