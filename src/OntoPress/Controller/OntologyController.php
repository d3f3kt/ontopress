<?php

namespace OntoPress\Controller;

use Symfony\Component\HttpFoundation\Request;
use OntoPress\Form\Ontology\Type\AddOntologyType;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Library\AbstractController;

class OntologyController extends AbstractController
{
    /**
     * Show all ontologies.
     *
     * @return string rendered twig template
     */
    public function showManageAction()
    {
        $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');
        $ontologys = $repository->findAll();
        $ontologyManageTable = array();

        foreach ($ontologys as $onto) {
            array_push($ontologyManageTable, array('id' => $onto->getID(), 'name' => $onto->getName(), 'form' => 10));
        }
        //print_r($ontologyManageTable);
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
        $author = wp_get_current_user();

        $ontology = new Ontology();
        $ontology->setAuthor($author->user_nicename)
            ->setDate(new \DateTime())
            ->addOntologyFile(new OntologyFile());

        $form = $this->createForm(new AddOntologyType(), $ontology, array(
            'cancel_link' => $this->generateRoute('ontopress_ontology'),
        ));

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isValid()) {
            $ontology->uploadFiles();

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
