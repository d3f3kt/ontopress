<?php

namespace OntoPress\Controller;

use OntoPress\Library\AbstractController;
use OntoPress\Form\Resource\Type\AddResourceDetailType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Resource Controller.
 * The Resource Controller is creating the dynamic Page Content for the Resource view.
 * It connects to the Database and renders the specific twig template for the different views.
 */
class ResourceController extends AbstractController
{

    /**
     * Handle the add request of a new resource.
     *
     * @return string rendered twig template
     */
    public function showAddAction()
    {
        return $this->render('resource/resourceAdd.html.twig', array());
    }

    /**
     * Handle the add details of a new resource and save them in database.
     *
     * @return string rendered twig template
     */
    public function showAddDetailsAction()//Request $request)
    {
        //get formId
        //$id = $request->get('id');
        $id = 2;

        //fetch the form by formId
        $formEntity = $this->getDoctrine()
            ->getRepository('OntoPress\Entity\Form')
            ->find($id);

        //create Formgenerator and hand over form

        $form = $this->createForm(new AddResourceDetailType(), null, array(
            'data' => $formEntity,
            'cancel_link' => $this->generateRoute('ontopress_resource'),
        ));

        return $this->render('resource/resourceAddDetails.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Show all resources.
     *
     * @return string rendered twig template
     */
    public function showManagementAction()
    {
        $resourceManageTable = array(
            array('id' => 1, 'title' => 'Augustusplatz', 'author' => 'k00ni', 'date' => '20.Jan 2016'),
            array('id' => 2, 'title' => 'Uni Campus', 'author' => 'k00ni', 'date' => '22.Jan 2016'),
            array('id' => 3, 'title' => 'Oper Leipzig', 'author' => 'd3f3ct', 'date' => '22.Jan 2016'),
        );

        return $this->render('resource/resourceManagement.html.twig', array(
            'resourceManageTable' => $resourceManageTable
        ));
    }

    /**
     * Handle the delete request of one or more resources.
     *
     * @return string rendered twig template
     */
    public function showDeleteAction()
    {
        return $this->render('resource/resourceDelete.html.twig', array());
    }
}
