<?php
namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stage')]
class StageController extends AbstractController
{
    #[Route('/', name: 'stage_index', methods: ['GET'])]
    public function index(StageRepository $stageRepository): Response
    {
        $stages = $stageRepository->findAll();
        return $this->render('stage/index.html.twig', [
            'stages' => $stages,
        ]);
    }

    #[Route('/new', name: 'stage_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $stage = new Stage();
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($stage);
            $em->flush();
            $this->addFlash('success', 'Stage créé.');
            return $this->redirectToRoute('stage_index');
        }

        return $this->render('stage/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'stage_show', methods: ['GET'])]
    public function show(Stage $stage): Response
    {
        return $this->render('stage/show.html.twig', [
            'stage' => $stage,
        ]);
    }

    #[Route('/{id}/edit', name: 'stage_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Stage $stage, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Stage modifié.');
            return $this->redirectToRoute('stage_index');
        }

        return $this->render('stage/edit.html.twig', [
            'form' => $form->createView(),
            'stage' => $stage,
        ]);
    }

    #[Route('/{id}/delete', name: 'stage_delete', methods: ['POST'])]
    public function delete(Request $request, Stage $stage, EntityManagerInterface $em): Response
    {
        // token CSRF si tu l'ajoutes au formulaire
        if ($this->isCsrfTokenValid('delete'.$stage->getId(), $request->request->get('_token'))) {
            $em->remove($stage);
            $em->flush();
            $this->addFlash('success', 'Stage supprimé.');
        }

        return $this->redirectToRoute('stage_index');
    }
}
