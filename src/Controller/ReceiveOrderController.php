<?php

namespace App\Controller;

use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ... other use statements

class ReceiveOrderController extends AbstractController
{
    #[Route('/receive-order', name: 'receive_order')]
    public function orderDetails(OrdersRepository $orderRepository): Response
    {
        // Fetch the order details from the database (assuming order ID is 1)
        $orderId = 1;
        $order = $orderRepository->find($orderId);

        return $this->render('receive_order.html.twig', [
            'orderId' => $orderId,
            'order' => $order, // Pass the order entity to the template if needed
        ]);
    }
}
