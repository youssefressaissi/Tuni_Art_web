<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use App\Services\QrCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/delivery')]
class DeliveryController extends AbstractController
{
    #[Route('/', name: 'app_delivery_index', methods: ['GET'])]
    public function index(DeliveryRepository $deliveryRepository, QrCodeService $qrcodeService): Response
    {
        $qrCode = $qrcodeService->qrcode(null);
        $delivery = new Delivery();

        return $this->render('delivery/index.html.twig', [
            'deliveries' => $deliveryRepository->findAll(),
            'qrCode' => $qrCode,
            'delivery' => $delivery,

        ]);
    }

    #[Route('/new', name: 'app_delivery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $delivery = new Delivery();
        $form = $this->createForm(DeliveryType::class, $delivery);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agency = $delivery->getAgency();
            if ($agency) {
                $agency->setNbDeliveries($agency->getNbDeliveries() + 1);
                $entityManager->persist($agency); // Persist the updated agency
            }
            $entityManager->persist($delivery);
            $entityManager->flush();


            return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery/new.html.twig', [
            'delivery' => $delivery,
            'form' => $form,
        ]);
    }

    #[Route('/{deliveryId}', name: 'app_delivery_show', methods: ['GET'])]
    public function show(Delivery $delivery): Response
    {
        return $this->render('delivery/show.html.twig', [
            'delivery' => $delivery,
        ]);
    }

    #[Route('/{deliveryId}/edit', name: 'app_delivery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        $originalAgency = $delivery->getAgency(); // Store the original agency

        $form = $this->createForm(DeliveryType::class, $delivery);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newAgency = $delivery->getAgency();

            if ($originalAgency !== $newAgency) {
                // Agency changed
                if ($originalAgency) {
                    $originalAgency->setNbDeliveries($originalAgency->getNbDeliveries() - 1);
                    $entityManager->persist($originalAgency);
                }
                if ($newAgency) {
                    $newAgency->setNbDeliveries($newAgency->getNbDeliveries() + 1);
                    $entityManager->persist($newAgency);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery/edit.html.twig', [
            'delivery' => $delivery,
            'form' => $form,
        ]);
    }

    #[Route('/{deliveryId}', name: 'app_delivery_delete', methods: ['POST'])]
    public function delete(Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $delivery->getDeliveryId(), $request->request->get('_token'))) {
            $entityManager->remove($delivery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
    }
}
