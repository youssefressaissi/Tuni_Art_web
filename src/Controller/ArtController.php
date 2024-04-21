<?php

namespace App\Controller;

use App\Entity\Art;
use App\Form\ArtType;
use App\Repository\ArtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;

#[Route('/art')]
class ArtController extends AbstractController
{
    
    public function showImage($filename)
    {
        $filePath = '/assets/images/' . $filename; // Path to your image file

        // Check if the file exists
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The image does not exist');
        }

        // Create a BinaryFileResponse
        $response = new BinaryFileResponse($filePath);
        $response->headers->set('Content-Type', 'image/jpeg'); // Adjust content type if necessary

        // Set the disposition to inline to display the image in the browser
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);

        return $response;
    }

    #[Route('/all', name: 'app_art_index', methods: ['GET'])]
    public function index(ArtRepository $artRepository): Response
    {
        return $this->render('art/index.html.twig', [
            'art' => $artRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_art_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $art = new Art();
        $form = $this->createForm(ArtType::class, $art);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageId')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_art_new');
                }

                $art->setImageId($newFilename);
            }

            $entityManager->persist($art);
            $entityManager->flush();

            return $this->redirectToRoute('app_art_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('art/new.html.twig', [
            'art' => $art,
            'form' => $form,
        ]);
    }

    #[Route('/{artRef}', name: 'app_art_show', methods: ['GET'])]
    public function show(Art $art): Response
    {
        return $this->render('art/show.html.twig', [
            'art' => $art,
        ]);
    }

    #[Route('/{artRef}/edit', name: 'app_art_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Art $art, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtType::class, $art);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_art_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('art/edit.html.twig', [
            'art' => $art,
            'form' => $form,
        ]);
    }

    #[Route('/{artRef}', name: 'app_art_delete', methods: ['POST'])]
    public function delete(Request $request, Art $art, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$art->getArtRef(), $request->request->get('_token'))) {
            $entityManager->remove($art);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_art_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{artRef}/generate-qr-code', name: 'app_art_generate_qr_code')]
public function generateQRCode(Art $art): Response
{
$qrCodeData = 'Art Details:' . PHP_EOL;
$qrCodeData .= 'Art Reference: ' . $art->getArtRef() . PHP_EOL;
$qrCodeData .= 'Title: ' . $art->getArtTitle() . PHP_EOL;
$qrCodeData .= 'Price: ' . $art->getArtPrice() . PHP_EOL;
$qrCodeData .= 'Type: ' . $art->getType() . PHP_EOL;
$qrCodeData .= 'Creation Date: ' . $art->getCreation()->format('Y-m-d') . PHP_EOL;
$qrCodeData .= 'Description: ' . $art->getDescription() . PHP_EOL;
$qrCodeData .= 'Style: ' . $art->getStyle() . PHP_EOL;
$qrCodeData .= 'Artist ID: ' . $art->getArtistId() . PHP_EOL;
$qrCodeData .= 'Views: ' . $art->getArtViews() . PHP_EOL;
$qrCodeData .= 'Availability: ' . ($art->isIsAvailable() ? 'Available' : 'Not Available') . PHP_EOL;
    // Create a new QR code for the specified art piece
    $qrCode = QrCode::create($qrCodeData)
        ->setEncoding(new Encoding('UTF-8'))
        ->setMargin(10)
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

    // Generate the QR code image
    $writer = new PngWriter();
    $dataUri = $writer->write($qrCode)->getDataUri();

    // Render the QR code in a template
    return $this->renderForm('art/qr_code.html.twig', [
        'qrCode' => $dataUri,
        'art' => $art,
    ]);
}
}