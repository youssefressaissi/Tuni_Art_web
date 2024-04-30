<?php

namespace App\Controller;

use App\Entity\DeliveryAgency;
use App\Form\DeliveryAgencyType;
use App\Repository\DeliveryAgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/delivery/agency')]
class DeliveryAgencyController extends AbstractController
{
    #[Route('/', name: 'app_delivery_agency_index', methods: ['GET'])]
    public function index(DeliveryAgencyRepository $deliveryAgencyRepository): Response
    {
        return $this->render('delivery_agency/index.html.twig', [
            'delivery_agencies' => $deliveryAgencyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_delivery_agency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deliveryAgency = new DeliveryAgency();
        $form = $this->createForm(DeliveryAgencyType::class, $deliveryAgency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($deliveryAgency);
            $entityManager->flush();

            return $this->redirectToRoute('app_delivery_agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery_agency/new.html.twig', [
            'delivery_agency' => $deliveryAgency,
            'form' => $form,
        ]);
    }

    #[Route('/{agencyId}', name: 'app_delivery_agency_show', methods: ['GET'])]
    public function show(DeliveryAgency $deliveryAgency): Response
    {
        return $this->render('delivery_agency/show.html.twig', [
            'delivery_agency' => $deliveryAgency,
        ]);
    }

    #[Route('/{agencyId}/edit', name: 'app_delivery_agency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DeliveryAgency $deliveryAgency, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeliveryAgencyType::class, $deliveryAgency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_delivery_agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery_agency/edit.html.twig', [
            'delivery_agency' => $deliveryAgency,
            'form' => $form,
        ]);
    }

    #[Route('/{agencyId}', name: 'app_delivery_agency_delete', methods: ['POST'])]
    public function delete(Request $request, DeliveryAgency $deliveryAgency, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deliveryAgency->getAgencyId(), $request->request->get('_token'))) {
            $entityManager->remove($deliveryAgency);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_delivery_agency_index', [], Response::HTTP_SEE_OTHER);
    }
}
