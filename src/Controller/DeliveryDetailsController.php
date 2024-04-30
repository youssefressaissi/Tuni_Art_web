<?php

namespace App\Controller;

use App\Form\DeliveryType;
use App\Entity\Delivery;
use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;


class DeliveryDetailsController extends AbstractController
{
    #[Route('/delivery-details', name: 'delivery_details')]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Create a new instance of Delivery
        $delivery = new Delivery();


        // Set default values
        $order = $entityManager->getRepository(Orders::class)->find(1); // Assuming order_id is 1
        $delivery->setOrder($order); // Assuming order_id is 1
        $delivery->setEstimatedDate(new \DateTime('+3 days')); // +3 days from today
        $delivery->setDeliveryFees(7.0); // Default delivery fees
        $delivery->setState(false); // Default state

        // Create the form using DeliveryType
        $form = $this->createForm(DeliveryType::class, $delivery);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission and persist delivery entity
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($delivery);
            $entityManager->flush();

            // Redirect to the receive order page
            // return new RedirectResponse($this->generateUrl('receive_order'));

            // Render delivery notification template and pass delivery entity
            return $this->render('delivery_notification.html.twig', [
                'delivery' => $delivery,
            ]);

            //Make notification saying delivery $delivery->getId() has been created successfully and redirect to the receive order page 
            // $this->addFlash('success', 'Delivery ' . $delivery->getDeliveryId() . ' has been created successfully');
            // return $this->redirectToRoute('receive_order');


            return $this->redirectToRoute('app_delivery_index');
        }

        return $this->render('delivery_details.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/delivery-details', name: 'delivery_details')] // Ensure name is "delivery_details"
    // public function index(Request $request): Response
    // {
    //     // Create form (optional, if you need to capture additional details)
    //     $form = $this->createForm(DeliveryType::class);
    //     $form->handleRequest($request);

    //     // ... handle form submission and Delivery entity creation ...

    //     return $this->render('delivery_details.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
}
