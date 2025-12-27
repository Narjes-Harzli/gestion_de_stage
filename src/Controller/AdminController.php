<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\StageRepository;
use App\Repository\DemandestageRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        UserRepository $userRepository,
        StageRepository $stageRepository,
        DemandestageRepository $demandestageRepository,
        DepartmentRepository $departmentRepository
    ): Response {
        // Récupérer les statistiques
        $etudiants = count($userRepository->findBy(['role' => 'ETUDIANT']));
        $encadrants = count($userRepository->findBy(['role' => 'ENCADRANT']));
        $stages = count($stageRepository->findAll());
        $demandes = count($demandestageRepository->findAll());
        $departments = count($departmentRepository->findAll());

        return $this->render('admin/index.html.twig', [
            'etudiants' => $etudiants,
            'encadrants' => $encadrants,
            'stages' => $stages,
            'demandes' => $demandes,
            'departments' => $departments,
        ]);
    }
}
