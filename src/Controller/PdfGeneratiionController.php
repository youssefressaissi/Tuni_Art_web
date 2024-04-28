<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Repository\OrderRepository;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratiionController extends AbstractController
{

    #[Route('/pdf', name: 'app_generate_pdf', methods: ['GET'])]
    public function generatePdfAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the selected arts list and total amount from the session
        $selectedArts = json_decode($request->getSession()->get('selectedArts'), true);
        $totalAmount = $request->getSession()->get('totalAmount');

        // Fetch orders from the database
        $orders = $entityManager
            ->getRepository(Order::class)
            ->findAll();

        // Create a Dompdf instance
        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isPhpEnabled', true);
        $dompdf = new Dompdf($pdfOptions);

        // Generate HTML content for the PDF
        $html = $this->renderView('order/pdf.html.twig', [
            'orders' => $orders,
            'selectedArts' => $selectedArts,
            'totalAmount' => $totalAmount,
        ]);

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A5', 'portrait');

        // Render PDF
        $dompdf->render();

        // Generate a unique filename for the PDF
        $filename = 'document_' . uniqid() . '.pdf';

        // Save the PDF to a temporary location
        $output = $dompdf->output();
        file_put_contents($filename, $output);

        // Create a response with the PDF file
        $response = new Response(file_get_contents($filename));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Pragma', 'private');
        $response->headers->set('Expires', '0');

        // Delete the temporary PDF file
        unlink($filename);

        return $response;
    }

}
