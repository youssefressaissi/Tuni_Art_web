<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gallery')]
class GalleryController extends AbstractController
{
    #[Route('/', name: 'app_gallery_index', methods: ['GET'])]
    public function index(GalleryRepository $galleryRepository): Response
    {
        return $this->render('gallery/index.html.twig', [
            'galleries' => $galleryRepository->findAll(),
        ]);
    }

    #[Route('/all_back', name: 'app_gallery_all_index', methods: ['GET'])]
public function index_back(GalleryRepository $galleryRepository): Response
{
    // Retrieve all galleries
    $galleries = $galleryRepository->findAll();
    
    // Initialize an array to store location counts
    $locationCounts = [];
    
    // Count galleries for each location
    foreach ($galleries as $gallery) {
        $location = $gallery->getGalleryLocation();
        
        // Increment the count for this location
        if (!isset($locationCounts[$location])) {
            $locationCounts[$location] = 1;
        } else {
            $locationCounts[$location]++;
        }
    }
    
    // Prepare an array to hold location names and counts
    $locationData = [];
    
    // Populate the location data array
    foreach ($locationCounts as $location => $count) {
        $locationData[] = [
            'name' => $location,
            'count' => $count,
        ];
    }
    
    return $this->render('gallery/back_index.html.twig', [
        'locationData' => $locationData,
        'galleries' => $galleryRepository->findAll(),
    ]);
}

    

    #[Route('/new', name: 'app_gallery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gallery);
            $entityManager->flush();

            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gallery/new.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }
    #[Route('/gallery')]

    #[Route('/search', name: 'app_gallery_search', methods: ['GET'])]
    public function search(Request $request, GalleryRepository $galleryRepository): Response
{
    // Get the search query from the request
    $searchQuery = $request->query->get('search_query');

    // Perform the search by name using GalleryRepository
    $galleriesByName = $galleryRepository->searchByName($searchQuery);

    // Perform the search by location using GalleryRepository
    $galleriesByLocation = $galleryRepository->searchByLocation($searchQuery);

    // Merge the results from both searches
    $mergedGalleries = array_merge($galleriesByName, $galleriesByLocation);

    // Render the template with the search results
    return $this->render('gallery/index.html.twig', [
        'galleries' => $mergedGalleries,
    ]);
}


    #[Route('/{galleryId}', name: 'app_gallery_show', methods: ['GET'])]
    public function show(Gallery $gallery): Response
    {
        return $this->render('gallery/show.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    #[Route('/{galleryId}/edit', name: 'app_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gallery $gallery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/{galleryId}', name: 'app_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, Gallery $gallery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gallery->getGalleryId(), $request->request->get('_token'))) {
            $entityManager->remove($gallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gallery_all_index', [], Response::HTTP_SEE_OTHER);
    }
}
