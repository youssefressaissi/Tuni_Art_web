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

#[Route('/art')]
class ArtController extends AbstractController
{
    /**
     * @Route("/image/{filename}", name="art_image_show")
     */
    public function showImage($filename)
    {
        $filePath = '/path/to/images/' . $filename; // Path to your image file

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $art = new Art();
        $form = $this->createForm(ArtType::class, $art);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    #[Route('/update-isavailable', name: 'update_isavailable', methods: ['POST'])]
    public function updateIsAvailable(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Step 3: Debugging
        $artRef = $request->request->get('art_ref');
        var_dump($artRef); // Debugging statement
    
        // Find the art entity by its reference
        $art = $entityManager->getRepository(Art::class)->findOneBy(['artRef' => $artRef]);
        var_dump($art); 
    
        if ($art) {
            $art->setIsavailable(false); 
            $entityManager->persist($art);
            $entityManager->flush();
            $this->addFlash('success', 'Art item added successfully!');
        } else {
            $this->addFlash('error', 'Art item not found!');
        }
    
        return $this->redirectToRoute('app_art_index');
    }

}
