<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\StageRepository;
use App\Repository\DemandestageRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_public')]
    public function public(
        UserRepository $userRepository,
        StageRepository $stageRepository,
        DemandestageRepository $demandestageRepository,
        DepartmentRepository $departmentRepository
    ): Response {
        // Statistiques pour la page publique
        $etudiants = count($userRepository->findBy(['role' => 'ETUDIANT']));
        $stages = count($stageRepository->findAll());
        $demandes = count($demandestageRepository->findAll());
        $departments = count($departmentRepository->findAll());

        return $this->render('home/index.html.twig', [
            'etudiants' => $etudiants,
            'stages' => $stages,
            'demandes' => $demandes,
            'departments' => $departments,
        ]);
    }

    #[Route('/dashboard', name: 'app_home')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }

        if ($this->isGranted('ROLE_ENCADRANT')) {
            return $this->redirectToRoute('app_encadrant_dashboard');
        }

        if ($this->isGranted('ROLE_ETUDIANT')) {
            return $this->redirectToRoute('app_etudiant_dashboard_index');
        }

        return $this->redirectToRoute('app_login');
    }
}
