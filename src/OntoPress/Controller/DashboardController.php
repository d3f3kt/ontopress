<?php
namespace OntoPress\Controller;

use OntoPress\Libary\Controller;

class DashboardController extends Controller
{
    public function showDashboardAction()
    {
        return $this->render('dashboard/dashboard.html.twig', array());
    }
}
