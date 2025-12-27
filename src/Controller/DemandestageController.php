<?php

namespace App\Controller;

use App\Entity\Demandestage;
use App\Form\DemandestageType;
use App\Repository\DemandestageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demandestage')]
final class DemandestageController extends AbstractController
{
    private function denyUnlessAdminOrEncadrant(): void
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ENCADRANT')) {
            throw $this->createAccessDeniedException();
        }
    }

    private function denyUnlessCanAccessDemand(Demandestage $demandestage): void
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return;
        }

        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isGranted('ROLE_ETUDIANT')) {
            if ($demandestage->getEtudiant()?->getId() !== $user->getId()) {
                throw $this->createAccessDeniedException();
            }
            return;
        }

        if ($this->isGranted('ROLE_ENCADRANT')) {
            if ($demandestage->getStage()?->getEncadrant()?->getId() !== $user->getId()) {
                throw $this->createAccessDeniedException();
            }
            return;
        }

        throw $this->createAccessDeniedException();
    }

    #[Route(name: 'app_demandestage_index', methods: ['GET'])]
    public function index(DemandestageRepository $demandestageRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $demandes = $demandestageRepository->findAll();
        } elseif ($this->isGranted('ROLE_ENCADRANT')) {
            $demandes = $demandestageRepository->createQueryBuilder('d')
                ->join('d.stage', 's')
                ->andWhere('s.encadrant = :u')
                ->setParameter('u', $user)
                ->orderBy('d.id', 'DESC')
                ->getQuery()
                ->getResult();
        } else {
            $demandes = $demandestageRepository->createQueryBuilder('d')
                ->andWhere('d.etudiant = :u')
                ->setParameter('u', $user)
                ->orderBy('d.id', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return $this->render('demandestage/index.html.twig', [
            'demandestages' => $demandes,
        ]);
    }

    #[Route('/new', name: 'app_demandestage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ETUDIANT')) {
            throw $this->createAccessDeniedException();
        }

        $demandestage = new Demandestage();
        $demandestage->setEtudiant($this->getUser());
        $demandestage->setStatut('En attente');
        $demandestage->setDatedemande(new \DateTime('now'));

        $form = $this->createForm(DemandestageType::class, $demandestage, [
            'include_etudiant' => false,
            'include_statut' => false,
            'include_date' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandestage);
            $entityManager->flush();

            return $this->redirectToRoute('app_demandestage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demandestage/new.html.twig', [
            'demandestage' => $demandestage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demandestage_show', methods: ['GET'])]
    public function show(Demandestage $demandestage): Response
    {
        $this->denyUnlessCanAccessDemand($demandestage);

        return $this->render('demandestage/show.html.twig', [
            'demandestage' => $demandestage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demandestage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demandestage $demandestage, EntityManagerInterface $entityManager): Response
    {
        $this->denyUnlessCanAccessDemand($demandestage);

        if ($this->isGranted('ROLE_ETUDIANT')) {
            $form = $this->createForm(DemandestageType::class, $demandestage, [
                'include_etudiant' => false,
                'include_statut' => false,
                'include_date' => false,
                'include_followup' => false,
                'include_evaluation' => false,
                'include_fin_stage' => false,
            ]);
        } else {
            $form = $this->createForm(DemandestageType::class, $demandestage, [
                'include_etudiant' => $this->isGranted('ROLE_ADMIN'),
                'include_statut' => true,
                'include_date' => true,
                'include_followup' => true,
                'include_evaluation' => true,
                'include_fin_stage' => true,
            ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_ENCADRANT')) {
                $demandestage->setDateDecision(new \DateTimeImmutable('now'));
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_demandestage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demandestage/edit.html.twig', [
            'demandestage' => $demandestage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demandestage_delete', methods: ['POST'])]
    public function delete(Request $request, Demandestage $demandestage, EntityManagerInterface $entityManager): Response
    {
        $this->denyUnlessCanAccessDemand($demandestage);

        if ($this->isCsrfTokenValid('delete'.$demandestage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demandestage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demandestage_index', [], Response::HTTP_SEE_OTHER);
    }
}
