<?php

namespace App\Controller;

use App\Repository\StageRepository;
use App\Repository\DemandestageRepository;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EncadrantDashboardController extends AbstractController
{
    #[Route('/encadrant/dashboard', name: 'app_encadrant_dashboard')]
    public function index(
        StageRepository $stageRepository,
        DemandestageRepository $demandestageRepository,
        DocumentRepository $documentRepository,
        UserRepository $userRepository
    ): Response {
        $user = $this->getUser();
        
        // Récupérer les stages de l'encadrant
        $mesStages = $stageRepository->findBy(['encadrant' => $user]);
        $mesStagesRecents = $stageRepository->findBy(
            ['encadrant' => $user], 
            ['id' => 'DESC'], 
            3
        );
        
        // Récupérer les demandes pour les stages de l'encadrant
        $demandesEnAttente = [];
        $demandesRecentes = [];
        foreach ($mesStages as $stage) {
            $demandesStage = $demandestageRepository->findBy(['stage' => $stage]);
            foreach ($demandesStage as $demande) {
                if ($demande->getStatut() === 'EN_ATTENTE') {
                    $demandesEnAttente[] = $demande;
                }
                $demandesRecentes[] = $demande;
            }
        }
        
        // Limiter les demandes récentes
        $demandesRecentes = array_slice($demandesRecentes, 0, 3);
        
        // Récupérer les étudiants encadrés
        $etudiantsEncadres = [];
        foreach ($mesStages as $stage) {
            $demandesStage = $demandestageRepository->findBy(['stage' => $stage]);
            foreach ($demandesStage as $demande) {
                $etudiant = $demande->getEtudiant();
                if (!in_array($etudiant, $etudiantsEncadres)) {
                    $etudiantsEncadres[] = $etudiant;
                }
            }
        }
        
        // Limiter les étudiants
        $etudiantsEncadres = array_slice($etudiantsEncadres, 0, 3);
        
        // Récupérer les documents à valider
        $documentsAValider = [];
        foreach ($etudiantsEncadres as $etudiant) {
            // Récupérer les demandes de l'étudiant
            $demandesEtudiant = $demandestageRepository->findBy(['etudiant' => $etudiant]);
            foreach ($demandesEtudiant as $demande) {
                // Récupérer les documents de cette demande
                $documentsDemande = $documentRepository->findBy(['demandestage' => $demande]);
                foreach ($documentsDemande as $document) {
                    if ($document->getStatutValidation() === 'En attente') {
                        $documentsAValider[] = $document;
                    }
                }
            }
        }
        
        // Limiter les documents
        $documentsAValider = array_slice($documentsAValider, 0, 3);

        return $this->render('encadrant_dashboard/index.html.twig', [
            'mes_stages' => count($mesStages),
            'mes_etudiants' => count($etudiantsEncadres),
            'demandes_attente' => count($demandesEnAttente),
            'documents_valider' => count($documentsAValider),
            'mes_stages_recents' => $mesStagesRecents,
            'demandes_recentes' => $demandesRecentes,
            'etudiants_encadres' => $etudiantsEncadres,
            'documents_attente' => $documentsAValider,
        ]);
    }
}
