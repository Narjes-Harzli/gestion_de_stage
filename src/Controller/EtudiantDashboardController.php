<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EtudiantDashboardController extends AbstractController
{
    #[Route('/etudiant/dashboard', name: 'app_etudiant_dashboard')]
    public function index(): Response
    {
        return $this->render('etudiant_dashboard/index.html.twig', [
            'controller_name' => 'EtudiantDashboardController',
        ]);
    }
}
