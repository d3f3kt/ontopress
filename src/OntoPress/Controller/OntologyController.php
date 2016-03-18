<?php

namespace OntoPress\Controller;

use Symfony\Component\HttpFoundation\Request;
use OntoPress\Form\Ontology\Type\AddOntologyType;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Libary\AbstractController;

class OntologyController extends AbstractController
{
    /**
     * Show all ontologies.
     *
     * @return string rendered twig template
     */
    public function showManageAction()
    {
        $ontologyManageTable = array(
            array('id' => 1, 'ontology' => 'Gebäude', 'form' => 10),
            array('id' => 2, 'ontology' => 'Plätze', 'form' => 5),
            array('id' => 3, 'ontology' => 'Kirchen', 'form' => 8),
        );

        return $this->render('ontology/managePage.html.twig', array(
            'ontologyManageTable' => $ontologyManageTable,
        ));
    }

    /**
     * Handle the delete request of one ontology.
     *
     * @return string rendered twig template
     */
    public function showDeleteAction()
    {
        return $this->render('ontology/delete.html.twig', array());
    }

    /**
     * Handle the upload of one or more ontology files and save them in database.
     *
     * @return string rendered twig template
     */
    public function showAddAction()
    {
        $ontology = new Ontology();
        $ontology->addOntologyFile(new OntologyFile());

        $form = $this->createForm(new AddOntologyType(), $ontology, array(
            'cancel_link' => $this->generateRoute('ontopress_ontology'),
        ));

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isValid()) {
            $author = wp_get_current_user();
            $ontology->setAuthor($author->user_nicename);
            $ontology->setDate(new \DateTime());
            $ontology->uploadFiles();

            print_r($ontology);

            $this->getDoctrine()->persist($ontology);
            $this->getDoctrine()->flush();

            $this->addFlashMessage(
                'success',
                'Ontologie erfolgreich hochgeladen.'
            );
        }

        return $this->render('ontology/ontologyAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
