<?php
namespace OntoPress\Controller;

use OntoPress\Libary\Controller;

class ResourceController extends Controller
{
    public function showAddAction()
    {
        return $this->render('resource/resourceAdd.html.twig', array());
    }
}
