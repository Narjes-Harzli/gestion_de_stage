<?php

namespace App\Controller;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Repository\DemandestageRepository;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/encadrant')]
final class EncadrantSpaceController extends AbstractController
{
    #[Route('/stages', name: 'app_encadrant_space_stages', methods: ['GET'])]
    public function stages(StageRepository $stageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENCADRANT');

        $stages = $stageRepository->createQueryBuilder('s')
            ->andWhere('s.encadrant = :u')
            ->setParameter('u', $this->getUser())
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('encadrant_space/stages.html.twig', [
            'stages' => $stages,
        ]);
    }

    #[Route('/demandes', name: 'app_encadrant_space_demandes', methods: ['GET'])]
    public function demandes(DemandestageRepository $demandestageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENCADRANT');

        $demandes = $demandestageRepository->createQueryBuilder('d')
            ->join('d.stage', 's')
            ->andWhere('s.encadrant = :u')
            ->setParameter('u', $this->getUser())
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('encadrant_space/demandes.html.twig', [
            'demandestages' => $demandes,
        ]);
    }

    #[Route('/etudiants', name: 'app_encadrant_space_etudiants', methods: ['GET'])]
    public function etudiants(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENCADRANT');

        $users = $entityManager->createQueryBuilder()
            ->select('DISTINCT u')
            ->from('App\\Entity\\User', 'u')
            ->join('App\\Entity\\Demandestage', 'd', 'WITH', 'd.etudiant = u')
            ->join('d.stage', 's')
            ->andWhere('s.encadrant = :enc')
            ->setParameter('enc', $this->getUser())
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('encadrant_space/etudiants.html.twig', [
            'etudiants' => $users,
        ]);
    }

    #[Route('/documents', name: 'app_encadrant_space_documents', methods: ['GET'])]
    public function documents(DocumentRepository $documentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENCADRANT');

        $documents = $documentRepository->createQueryBuilder('doc')
            ->join('doc.demandestage', 'd')
            ->join('d.stage', 's')
            ->andWhere('s.encadrant = :u')
            ->setParameter('u', $this->getUser())
            ->orderBy('doc.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('encadrant_space/documents.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/documents/{id<\\d+>}/valider', name: 'app_encadrant_space_document_valider', methods: ['POST'])]
    public function validerDocument(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENCADRANT');

        if (!$this->isCsrfTokenValid('doc_status'.$document->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if ($document->getDemandestage()?->getStage()?->getEncadrant()?->getId() !== $this->getUser()?->getId()) {
            throw $this->createAccessDeniedException();
        }

        $document->setStatutValidation('Validé');
        $entityManager->flush();

        return $this->redirectToRoute('app_encadrant_space_documents');
    }

    #[Route('/documents/{id<\\d+>}/refuser', name: 'app_encadrant_space_document_refuser', methods: ['POST'])]
    public function refuserDocument(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENCADRANT');

        if (!$this->isCsrfTokenValid('doc_status'.$document->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if ($document->getDemandestage()?->getStage()?->getEncadrant()?->getId() !== $this->getUser()?->getId()) {
            throw $this->createAccessDeniedException();
        }

        $document->setStatutValidation('Refusé');
        $entityManager->flush();

        return $this->redirectToRoute('app_encadrant_space_documents');
    }
}
