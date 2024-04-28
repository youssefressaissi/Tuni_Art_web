<?php

namespace App\Controller;
use App\Entity\PdfGenerator;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(HttpClientInterface $httpClient): Response
    {
        // Fetch a random joke from the API
        $response = $httpClient->request('GET', 'https://api.chucknorris.io/jokes/random');
        $joke = $response->toArray()['value']; // Extract the joke from the response

        return $this->render('payment/index.html.twig', [
            'randomJoke' => $joke,
        ]);
    }

    #[Route("/save-total-amount", name: "save_total_amount", methods: ["POST"])]
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
    /**public function __construct(Dompdf $dompdf)
    {
        $this->dompdf = $dompdf;
    }
    public function generatePdf($id): Response
    {
        // Fetch payment data based on the ID
        $paymentData = $this->fetchPaymentData($id);

        // Generate the PDF content
        $html = $this->renderView('payment/pdf.html.twig', [
            'payment' => $paymentData, // Correct the variable name here
        ]);

        // Load HTML content
        $this->dompdf->loadHtml($html);

        // Set paper size and orientation
        $this->dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $this->dompdf->render();

        // Output PDF content
        return new Response($this->dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="payment_details.pdf"',
        ]);
    }
    private function fetchPaymentData($id)
    {
        // Implement logic to fetch payment data based on the ID
        // For demonstration purposes, returning dummy data
        return [
            'id' => $id,
            'amount' => 100.00, // Example payment amount
            'description' => 'Payment for Order #' . $id, // Example payment description
        ];
    }
**/

/**
 * Fetch payment data from the database based on the ID.
 * Replace this with your actual implementation.
 */
private function fetchPaymentData($id)
{
    // Implement logic to fetch payment data based on the ID
    // For demonstration purposes, returning dummy data
    return [
        'id' => $id,
        'amount' => 100.00, // Example payment amount
        'description' => 'Payment for Order #' . $id, // Example payment description
    ];
}

    
}
