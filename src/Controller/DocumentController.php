<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/document')]
final class DocumentController extends AbstractController
{
    #[Route(name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $docs = $documentRepository->findAll();
        } elseif ($this->isGranted('ROLE_ENCADRANT')) {
            $docs = $documentRepository->createQueryBuilder('doc')
                ->join('doc.demandestage', 'd')
                ->join('d.stage', 's')
                ->andWhere('s.encadrant = :u')
                ->setParameter('u', $user)
                ->orderBy('doc.id', 'DESC')
                ->getQuery()
                ->getResult();
        } else {
            $docs = $documentRepository->createQueryBuilder('doc')
                ->join('doc.demandestage', 'd')
                ->andWhere('d.etudiant = :u')
                ->setParameter('u', $user)
                ->orderBy('doc.id', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return $this->render('document/index.html.twig', [
            'documents' => $docs,
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ETUDIANT')) {
            throw $this->createAccessDeniedException();
        }

        $document = new Document();

        if (null === $document->getDateupload()) {
            $document->setDateupload(new \DateTime('now'));
        }

        $form = $this->createForm(DocumentType::class, $document, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($document->getDemandestage()?->getEtudiant()?->getId() !== $this->getUser()?->getId()) {
                throw $this->createAccessDeniedException();
            }

            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            if ($this->isGranted('ROLE_ETUDIANT')) {
                if ($document->getDemandestage()?->getEtudiant()?->getId() !== $user->getId()) {
                    throw $this->createAccessDeniedException();
                }
            } elseif ($this->isGranted('ROLE_ENCADRANT')) {
                if ($document->getDemandestage()?->getStage()?->getEncadrant()?->getId() !== $user->getId()) {
                    throw $this->createAccessDeniedException();
                }
            } else {
                throw $this->createAccessDeniedException();
            }
        }

        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ETUDIANT')) {
            throw $this->createAccessDeniedException();
        }

        if ($document->getDemandestage()?->getEtudiant()?->getId() !== $this->getUser()?->getId()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(DocumentType::class, $document, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($document->getDemandestage()?->getEtudiant()?->getId() !== $this->getUser()?->getId()) {
                throw $this->createAccessDeniedException();
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ETUDIANT')) {
            throw $this->createAccessDeniedException();
        }

        if ($document->getDemandestage()?->getEtudiant()?->getId() !== $this->getUser()?->getId()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
