<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantDashboardController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant_dashboard_index')]
    public function index(): Response
    {
        return $this->render('etudiant_dashboard/index.html.twig');
    }
}