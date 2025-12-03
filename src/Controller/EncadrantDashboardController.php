<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EncadrantDashboardController extends AbstractController
{
    #[Route('/encadrant/dashboard', name: 'app_encadrant_dashboard')]
    public function index(): Response
    {
        return $this->render('encadrant_dashboard/index.html.twig', [
            'controller_name' => 'EncadrantDashboardController',
        ]);
    }
}
