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
use App\Services\QrCodeService; // Import the service
use App\Service\WeatherService; // Import the WeatherService


class DeliveryDetailsController extends AbstractController
{

    #[Route('/delivery-details', name: 'delivery_details')]
    public function index(QrCodeService $qrcodeService,  Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response //WeatherService $weatherService,
    {

        $qrCode = null;


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

            // Fetch weather information for the destination provided by the user
            $destination = $delivery->getDestination(); // Assuming destination is a property of Delivery entity
            // $weatherData = $weatherService->getWeather($destination);

            $qrCode = $qrcodeService->qrcode($delivery->getDeliveryId());

            // Redirect to the receive order page
            // return new RedirectResponse($this->generateUrl('receive_order'));

            // Render delivery notification template and pass delivery entity
            return $this->render('delivery_notification.html.twig', [
                'delivery' => $delivery,
                'qrCode' => $qrCode,
                // 'weatherData' => $weatherData,
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
