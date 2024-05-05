<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(Request $request): Response
    {
        // Fetch a random joke from the Chuck Norris API
        $randomJoke = $this->fetchRandomJoke();

        return $this->render('payment/index.html.twig', [
            'randomJoke' => $randomJoke,
        ]);
    }

    #[Route('/create-order', name: 'create_order', methods: ['POST'])]
public function createOrder(Request $request, EntityManagerInterface $entityManager): Response
{
    // Get the total price from the request
    $totalPrice = $request->request->get('totalPrice');

    // Create a new order
    $order = new Order();
        $order->setOrderDate(new \DateTime());
        $order->setTotalPrice(432); // Set the total price
            // $order->setTotalprice(floatval($totalPrice));
        $order->setStatus(true);
        $order->setUid(3);


    // Set the total price

    // Set other properties of the order as needed

    // Persist and flush the order to the database
    $entityManager->persist($order);
    $entityManager->flush();

    // Redirect to a success page or perform other actions
    return $this->redirectToRoute('app_art_index');
}


    private function fetchRandomJoke(): string
    {
        // Fetch a random joke from the Chuck Norris API
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://api.chucknorris.io/jokes/random');
        $joke = $response->toArray()['value'];

        return $joke;
    }
}
