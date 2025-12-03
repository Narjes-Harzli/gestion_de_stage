<?php

namespace App\Controller;

use App\Entity\DemandeDeStage;
use App\Form\DemandeDeStageType;
use App\Repository\DemandeDeStageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
#[Route('/demande')]
class DemandeDeStageController extends AbstractController
{
    #[Route('/', name: 'demande_index', methods: ['GET'])]
    public function index(DemandeDeStageRepository $repo): Response
    {
        // un étudiant ne voit que ses propres demandes
        $demandes = $this->isGranted('ROLE_ADMIN')
            ? $repo->findAll()
            : $repo->findBy(['etudiant' => $this->getUser()]);

        return $this->render('demande_de_stage/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    #[Route('/new', name: 'demande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $demande = new DemandeDeStage();
        $form = $this->createForm(DemandeDeStageType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demande->setEtudiant($this->getUser());   // utilisateur connecté
            $demande->setStatut('en attente');
            $em->persist($demande);
            $em->flush();

            $this->addFlash('success', 'Demande envoyée avec succès.');
            return $this->redirectToRoute('demande_index');
        }

        return $this->render('demande_de_stage/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'demande_show', methods: ['GET'])]
    public function show(DemandeDeStage $demande): Response
    {
        $this->denyAccessUnlessGranted('VIEW', $demande); // voter si besoin
        return $this->render('demande_de_stage/show.html.twig', [
            'demande' => $demande,
        ]);
    }

    #[Route('/{id}/edit', name: 'demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeDeStage $demande, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $demande);

        $form = $this->createForm(DemandeDeStageType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Demande modifiée.');
            return $this->redirectToRoute('demande_index');
        }

        return $this->render('demande_de_stage/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'demande_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeDeStage $demande, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $demande);

        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $em->remove($demande);
            $em->flush();
            $this->addFlash('success', 'Demande supprimée.');
        }

        return $this->redirectToRoute('demande_index');
    }
}