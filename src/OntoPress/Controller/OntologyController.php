<?php

namespace OntoPress\Controller;

use Symfony\Component\HttpFoundation\Request;
use OntoPress\Form\Ontology\Type\AddOntologyType;
use OntoPress\Form\Ontology\Type\DeleteOntologyType;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Library\AbstractController;

/**
 * Ontology Controller.
 */
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
        $ontologyManageTable = $repository->findAll();

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
        $id = $_GET['id'];

        $ontologyDelete = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Ontology')
            ->find($id);

        if (!$ontologyDelete) {
            throw $this->createNotFoundException(
                'Die Ontologie die sie löschen möchten wurde nicht gefunden '
            );
        }

        $form = $this->createForm(new DeleteOntologyType(), $ontologyDelete, array(
        'cancel_link' => $this->generateRoute('ontopress_ontology'),
        ));

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isValid()) {
            $this->getDoctrine()->remove($ontologyDelete);
            $this->getDoctrine()->flush();
            $this->addFlashMessage(
                'success',
                'Ontologie erfolgreich gelöscht.'
            );
        }

        return $this->render('ontology/delete.html.twig', array(
            'ontologyDelete' => $ontologyDelete,
            'form' => $form->createView(),
        ));
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
