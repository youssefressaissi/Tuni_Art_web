<?php

namespace App\Controller;

use App\Entity\Art;
use App\Form\ArtType;
use App\Repository\ArtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/art')]
class ArtController extends AbstractController
{
    #[Route('/', name: 'app_art_index', methods: ['GET'])]
    public function index(ArtRepository $artRepository): Response
    {
        return $this->render('art/index.html.twig', [
            'art' => $artRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_art_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $art = new Art();
        $form = $this->createForm(ArtType::class, $art);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($art);
            $entityManager->flush();

            return $this->redirectToRoute('app_art_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('art/new.html.twig', [
            'art' => $art,
            'form' => $form,
        ]);
    }

    #[Route('/{artRef}', name: 'app_art_show', methods: ['GET'])]
    public function show(Art $art): Response
    {
        return $this->render('art/show.html.twig', [
            'art' => $art,
        ]);
    }

    #[Route('/{artRef}/edit', name: 'app_art_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Art $art, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtType::class, $art);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_art_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('art/edit.html.twig', [
            'art' => $art,
            'form' => $form,
        ]);
    }

    #[Route('/{artRef}', name: 'app_art_delete', methods: ['POST'])]
    public function delete(Request $request, Art $art, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$art->getArtRef(), $request->request->get('_token'))) {
            $entityManager->remove($art);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_art_index', [], Response::HTTP_SEE_OTHER);
    }
}
