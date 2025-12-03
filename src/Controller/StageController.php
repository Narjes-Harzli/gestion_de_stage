<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stages')]
class StageController extends AbstractController
{
    #[Route('/', name: 'stage_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(StageRepository $repo): Response
    {
        return $this->render('stage/index.html.twig', [
            'stages' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'stage_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $stage = new Stage();
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($stage);
            $em->flush();

            $this->addFlash('success', 'Stage créé avec succès.');
            return $this->redirectToRoute('stage_index');
        }

        return $this->render('stage/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'stage_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Stage $stage): Response
    {
        return $this->render('stage/show.html.twig', [
            'stage' => $stage,
        ]);
    }

    #[Route('/{id}/edit', name: 'stage_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Stage $stage, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Stage modifié avec succès.');
            return $this->redirectToRoute('stage_index');
        }

        return $this->render('stage/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'stage_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Stage $stage, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stage->getId(), $request->request->get('_token'))) {
            $em->remove($stage);
            $em->flush();
            $this->addFlash('success', 'Stage supprimé avec succès.');
        }

        return $this->redirectToRoute('stage_index');
    }
}
