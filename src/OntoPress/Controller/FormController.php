<?php
namespace OntoPress\Controller;

use OntoPress\Libary\Controller;

class FormController extends Controller
{
    public function showManageAction()
    {
        return $this->render('form/manageForms.html.twig', array());
    }
}
