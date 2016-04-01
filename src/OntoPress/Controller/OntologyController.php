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
     * @param Request $request HTTP Request
     *
     * @return string rendered twig template
     */
    public function showDeleteAction(Request $request)
    {
        $id = $request->get('id', 0);

        $ontologyDelete = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Ontology')
            ->find($id);

        if (!$ontologyDelete) {
            return $this->render('ontology/notFound.html.twig', array(
                'id' => $id,
            ));
        }

        $form = $this->createForm(new DeleteOntologyType(), $ontologyDelete, array(
            'cancel_link' => $this->generateRoute('ontopress_ontology'),
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->remove($ontologyDelete);
            $this->getDoctrine()->flush();
            $this->addFlashMessage(
                'success',
                'Ontologie erfolgreich gelöscht.'
            );

            return $this->redirectToRoute('ontopress_ontology');
        }

        return $this->render('ontology/delete.html.twig', array(
            'ontologyDelete' => $ontologyDelete,
            'form' => $form->createView(),
        ));
    }

    /**
     * Handle the upload of one or more ontology files and save them in database.
     *
     * @param Request $request HTTP Request
     *
     * @return string rendered twig template
     */
    public function showAddAction(Request $request)
    {
        $author = wp_get_current_user();

        $ontology = new Ontology();
        $ontology->setAuthor($author->user_nicename)
            ->setDate(new \DateTime())
            ->addOntologyFile(new OntologyFile());

        $form = $this->createForm(new AddOntologyType(), $ontology, array(
            'cancel_link' => $this->generateRoute('ontopress_ontology'),
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $ontology->uploadFiles();

            //$ontologyParser = $this->get('ontopress.ontology_parser');
            //$ontologyParser->parsing($ontology, true);

            $this->getDoctrine()->persist($ontology);
            $this->getDoctrine()->flush();

            $this->addFlashMessage(
                'success',
                'Ontologie erfolgreich hochgeladen.'
            );

            return $this->redirectToRoute('ontopress_ontology');
        }

        return $this->render('ontology/ontologyAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
