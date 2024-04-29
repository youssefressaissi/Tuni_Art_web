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
use Symfony\Component\HttpFoundation\JsonResponse;

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
    private function calculateArtStatistics(array $artViews): array
{
    // You can implement the logic to calculate statistics here
    // For example, calculate average, maximum, minimum, etc.
    $averageViews = array_sum($artViews) / count($artViews);
    $maxViews = max($artViews);
    $minViews = min($artViews);

    return [
        'average_views' => $averageViews,
        'max_views' => $maxViews,
        'min_views' => $minViews,
    ];
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
            $drawingData = $form->get('drawing_data')->getData();
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
            }elseif ($drawingData) {
                $newFilename = 'drawing_' . date('YmdHis') . '.png'; 
                $filePath = $this->getParameter('images_directory') . '/' . $newFilename; 
                
                // Decode and save the drawing data to a PNG file
                $decodedDrawingData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $drawingData));
                file_put_contents($filePath, $decodedDrawingData);
            
                // Set the image ID of the art entity to the filename
                $art->setImageId($filePath);
                return $this->render('art/new.html.twig', [
                    'imageId' => $filePath,
                ]);
            }

            $musicFile = $form->get('musicPath')->getData();
            if ($musicFile) {
            $originalMusicFilename = pathinfo($musicFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeMusicFilename = $slugger->slug($originalMusicFilename);
            $newMusicFilename = $safeMusicFilename . '-' . uniqid() . '.' . $musicFile->guessExtension();

                try {
                    $musicFile->move(
                        $this->getParameter('music_directory'),
                        $newMusicFilename
                    );
                } catch (FileException $e) {
            // Handle music upload error
            $this->addFlash('error', 'An error occurred while uploading the music.');
            return $this->redirectToRoute('app_art_new');
        }

        $art->setMusicPath($newMusicFilename);
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


    #[Route('/sort', name: 'app_art_sort', methods: ['GET'])]
    public function sortedList(Request $request, EntityManagerInterface $entityManager): Response
{
    $sortBy = $request->query->get('sort_by');

    switch ($sortBy) {
        case 'price_desc':
            $sortBy = ['artPrice' => 'DESC'];
            break;
        case 'views_desc':
            $sortBy = ['artViews' => 'DESC'];
            break;
        case 'name_asc':
            $sortBy = ['artTitle' => 'ASC'];
            break;
        default:
            $sortBy = ['artViews' => 'DESC'];
            break;
    }

    $art = $entityManager->getRepository(Art::class)->findByCriteria([], $sortBy);

    $artViews = [];
    foreach ($art as $artItem) {
        $artViews[] = $artItem->getArtViews();
    }

    $stats = $this->calculateArtStatistics($artViews);

    return $this->render('art/index.html.twig', [
        'art' => $art,
        'stats' => $stats,
    ]);
}

#[Route('/save-image', name: 'save_image', methods: ['POST'])]
public function saveImage(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Decode the JSON data from the request body
    $data = json_decode($request->getContent(), true);

    // Check if the image data exists
    if (isset($data['image'])) {
        // Extract the image data
        $imageData = $data['image'];

        // Create a unique filename for the image
        $filename = uniqid('image_') . date('YmdHis'). '.png';

        // Define the directory where the image will be saved
        $directory = $this->getParameter('images_directory');

        // Define the full path to the image file
        $filePath = $directory . '/' . $filename;

        // Save the image data to the file system
        try {
            // Decode the base64-encoded image data and save it to a file
            $decodedImageData = base64_decode(str_replace('data:image/png;base64,', '', $imageData));
            file_put_contents($filePath, $decodedImageData);

            $art = new Art();
             $art->setImageId($filename);
             $entityManager->persist($art);
             $entityManager->flush();

            // Return a success response with the path to the saved image
            return new JsonResponse(['success' => true, 'path' => $filename]);
        } catch (\Exception $e) {
            // Return an error response if saving the image fails
            return new JsonResponse(['success' => false, 'error' => 'Failed to save image.'], 500);
        }
    } else {
        // Return an error response if the image data is missing
        return new JsonResponse(['success' => false, 'error' => 'Image data is missing.'], 400);
    }
}
#[Route('/save-canvas-image', name: 'save_canvas_image', methods: ['POST'])]
public function saveCanvasImage(Request $request): Response
{
    // Get the image data from the request body
    $data = json_decode($request->getContent(), true);
    $imageData = $data['image'];

    // Generate a unique filename for the image
    $filename = uniqid('canvas_image_') . '.png';

    // Save the image to the assets/images directory
    $filePath = $this->getParameter('kernel.project_dir') . '/public/assets/images/' . $filename;
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);
    file_put_contents($filePath, $imageData);

    // Return JSON response indicating success and the path to the saved image
    return $this->json([
        'success' => true,
        'path' => '/assets/images/' . $filename,
    ]);
}


#[Route("/search", name:'art_search')]
public function search(Request $request, ArtRepository $artRepository): Response
{
    // Get search query and selected criteria from the request
    $searchQuery = $request->query->get('search_query');
    $criteria = $request->query->get('criterias');

    // Initialize criteria array
    $criteriaArray = [];

    // Add search query to criteria array based on selected criteria
    if ($criteria === 'artTitle') {
        $criteriaArray['artTitle'] = $searchQuery;
    } elseif ($criteria === 'type') {
        $criteriaArray['type'] = $searchQuery;
    }

    // Retrieve arts based on criteria
    $art = $artRepository->findByCriteria($criteriaArray);

    // Optionally, perform additional processing on retrieved arts if needed

    return $this->render('art/index.html.twig', [
        'art' => $art,
        
        
    ]);
}


#[Route('/{artRef}', name: 'app_art_show', methods: ['GET'])]
    public function show(Art $art,EntityManagerInterface $entityManager): Response
    {
        $art->incrementArtViews();
        $entityManager->flush();
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
public function generateQRCode(Art $art,EntityManagerInterface $entityManager): Response
{             
            $art->incrementArtViews();
            $entityManager->flush();
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