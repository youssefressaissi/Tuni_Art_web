<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Form\AuctionType;
use App\Repository\AuctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auction')]
class AuctionController extends AbstractController
{
    #[Route('/', name: 'app_auction_index', methods: ['GET'])]
    public function index(AuctionRepository $auctionRepository): Response
    {
        return $this->render('auction/index.html.twig', [
            'auctions' => $auctionRepository->findAll(),
        ]);
    }
    #[Route('/all_back', name: 'app_auction_all_index', methods: ['GET'])]
    public function index_back(AuctionRepository $auctionRepository): Response
    {
        return $this->render('auction/back_index.html.twig', [
            'auctions' => $auctionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_auction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auction = new Auction();
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auction);
            $entityManager->flush();

            return $this->redirectToRoute('app_auction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auction/new.html.twig', [
            'auction' => $auction,
            'form' => $form,
        ]);
    }

    #[Route('/{auctionRef}', name: 'app_auction_show', methods: ['GET'])]
    public function show(Auction $auction): Response
    {
        return $this->render('auction/show.html.twig', [
            'auction' => $auction,
        ]);
    }

    #[Route('/{auctionRef}/edit', name: 'app_auction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Auction $auction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_auction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auction/edit.html.twig', [
            'auction' => $auction,
            'form' => $form,
        ]);
    }

    #[Route('/{auctionRef}', name: 'app_auction_delete', methods: ['POST'])]
    public function delete(Request $request, Auction $auction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auction->getAuctionRef(), $request->request->get('_token'))) {
            $entityManager->remove($auction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_auction_index', [], Response::HTTP_SEE_OTHER);
    }
}
