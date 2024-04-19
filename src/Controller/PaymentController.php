<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig');
    }
      /**
     * @Route("/save-total-amount", name="save_total_amount", methods={"POST"})
     */
    public function saveTotalAmount(Request $request): Response
    {
        // Retrieve the total amount from the request data
        $requestData = json_decode($request->getContent(), true);
        $totalAmount = $requestData['totalPrice'];

        // Save the total amount to the database (replace this with your actual logic)
        // Example:
        $entityManager = $this->getDoctrine()->getManager();
        $order = new Order();
        $order->setTotalprice($totalAmount);
        $entityManager->persist($order);
        $entityManager->flush();

        return new Response('Total amount saved successfully!', Response::HTTP_OK);
    }
    
}
