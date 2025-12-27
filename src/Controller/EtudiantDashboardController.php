<?php

namespace App\Controller;

use App\Repository\DemandestageRepository;
use App\Repository\DocumentRepository;
use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantDashboardController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant_dashboard_index')]
    public function index(
        DemandestageRepository $demandestageRepository,
        DocumentRepository $documentRepository,
        StageRepository $stageRepository
    ): Response {
        $user = $this->getUser();
        
        // Récupérer les demandes de l'étudiant
        $mesDemandes = $demandestageRepository->findBy(['etudiant' => $user]);
        $mesDemandesRecentes = $demandestageRepository->findBy(
            ['etudiant' => $user], 
            ['datedemande' => 'DESC'], 
            5
        );
        
        // Récupérer les documents de l'étudiant (via les demandes)
        $mesDocuments = [];
        $mesDocumentsRecents = [];
        foreach ($mesDemandes as $demande) {
            $documentsDemande = $documentRepository->findBy(['demandestage' => $demande]);
            foreach ($documentsDemande as $document) {
                $mesDocuments[] = $document;
                $mesDocumentsRecents[] = $document;
            }
        }
        
        // Trier les documents récents par date
        usort($mesDocumentsRecents, function($a, $b) {
            return $b->getDateupload() <=> $a->getDateupload();
        });
        $mesDocumentsRecents = array_slice($mesDocumentsRecents, 0, 5);
        
        // Récupérer les stages disponibles
        $stagesDisponibles = $stageRepository->findBy([], [], 6);
        
        // Compter les stages validés
        $mesStages = 0;
        foreach ($mesDemandes as $demande) {
            if ($demande->getStatut() === 'VALIDE') {
                $mesStages++;
            }
        }

        return $this->render('etudiant_dashboard/index.html.twig', [
            'mes_demandes' => count($mesDemandes),
            'mes_stages' => $mesStages,
            'mes_documents' => count($mesDocuments),
            'mes_demandes_recentes' => $mesDemandesRecentes,
            'mes_documents_recents' => $mesDocumentsRecents,
            'stages_disponibles' => $stagesDisponibles,
        ]);
    }
}