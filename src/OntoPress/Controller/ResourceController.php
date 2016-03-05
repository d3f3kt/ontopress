<?php

namespace OntoPress\Controller;

use OntoPress\Libary\Controller;
use OntoPress\Form\Resource\AddResourceDetailForm;

class ResourceController extends Controller
{
    public function showAddAction()
    {
        return $this->render('resource/resourceAdd.html.twig', array());
    }

    public function showAddDetailsAction()
    {
        $form = $this->createForm(new AddResourceDetailForm(), array(
            'cancel_link' => $this->generateRoute('ontopress_resource'),
        ));

        return $this->render('resource/resourceAddDetails.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showManagementAction()
    {
        return $this->render('resource/resourceManagement.html.twig', array());
    }

    public function showDeleteAction()
    {
        return $this->render('resource/resourceDelete.html.twig', array());
    }
}
