<?php

namespace OntoPress\Controller;

use OntoPress\Libary\AbstractController;
use OntoPress\Form\Resource\Type\AddResourceDetailType;

class ResourceController extends AbstractController
{
    public function showAddAction()
    {
        return $this->render('resource/resourceAdd.html.twig', array());
    }

    public function showAddDetailsAction()
    {
        $form = $this->createForm(new AddResourceDetailType(), null, array(
            'cancel_link' => $this->generateRoute('ontopress_resource'),
        ));

        return $this->render('resource/resourceAddDetails.html.twig', array(
            'form' => $form->createView(),
        ));
    }

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

    public function showDeleteAction()
    {
        return $this->render('resource/resourceDelete.html.twig', array());
    }
}
