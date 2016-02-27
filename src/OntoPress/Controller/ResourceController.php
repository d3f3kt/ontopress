<?php
namespace OntoPress\Controller;

use OntoPress\Libary\Controller;

class ResourceController extends Controller
{
    public function showAddAction()
    {
        return $this->render('resource/resourceAdd.html.twig', array());
    }

    public function showAddDetailsAction()
    {
        return $this->render('resource/resourceAddDetails.html.twig', array());
    }

    public function showManagementAction()
    {
        return $this->render('resource/resourceManagement.html.twig', array());
    }
}
