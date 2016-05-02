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
 * The Ontology Controller is creating the dynamic Page Content for the Ontology view.
 * It connects to the Database and renders the specific twig template for the different views.
 */
class OntologyController extends AbstractController
{
    /**
     * Creates the dynamic Table content for the Ontology manage view.
     * It fetches all Ontologies from the Database and renders the twig template.
     *
     * @return string rendered twig template
     */
    public function showManageAction(Request $request)
    {
        if ($request->get('sort') !== null) {
            $ontologyManageTable = $this->showSortAction($request);
        } else {

            //fetch all Ontologies from the Database
            $repository = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');
            $ontologyManageTable = $repository->findAll();
        }
            //render twig template
            return $this->render('ontology/managePage.html.twig', array(
                'ontologyManageTable' => $ontologyManageTable,
            ));
    }

    /**
     * Handle the delete request of one ontology.
     * It fetches the selected Ontology from the Database, renders a twig template and removes the Ontology if
     * the submit button is enabled.
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
        $ontologyForms = $ontologyDelete->getOntologyForms();
        
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
            'ontologyForms' => $ontologyForms,
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

            $ontologyParser = $this->get('ontopress.ontology_parser');
            $ontologyParser->parsing($ontology, true);

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

    private function showSortAction(Request $request)
    {
        $ontologyRepository = $this->getDoctrine()->getRepository('OntoPress\Entity\Ontology');
        $col = $request->get('sort');
        switch ($col) {
            case 'id':
                $ontologySort = $ontologyRepository->findBy(array(), array('id' => 'ASC'));
                break;
            case 'ontology':
                $ontologySort = $ontologyRepository->findBy(array(), array('ontology' => 'ASC'));
                break;
            case 'formCount':
                $ontologySort = $ontologyRepository->findBy(array(), array('formCount' => 'ASC'));
                break;
            case 'author':
                $ontologySort = $ontologyRepository->findBy(array(), array('author' => 'ASC'));
                break;
            case 'date':
                $ontologySort = $ontologyRepository->findBy(array(), array('date' => 'ASC'));
                break;
        }

        return $ontologySort;
    }
}
