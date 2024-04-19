<?php

namespace App\Controller;

use App\Entity\Cart;
 // Import the Art entity class
use App\Entity\User; 
use App\Form\CartType;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/list', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $cartRepository->findAll(),
        ]);
    }
   
#[Route('/new', name: 'app_cart_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
{
    $cart = new Cart();
    $form = $this->createForm(CartType::class, $cart);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // If the form is submitted and valid, proceed to persist the cart
        $entityManager->persist($cart);
        $entityManager->flush();

        // Redirect to the index page or any other page as needed
        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }

    // If the form is not valid, collect the validation errors
    $errors = $validator->validate($cart);

    // Create an array to store error messages
    $errorMessages = [];
    foreach ($errors as $error) {
        $errorMessages[] = $error->getMessage();
    }

    // Render the form with error messages
    return $this->renderForm('cart/new.html.twig', [
        'cart' => $cart,
        'form' => $form,
        'errors' => $errorMessages, // Pass error messages to the template
    ]);
}

    #[Route('/{cartRef}', name: 'app_cart_show', methods: ['GET'])]
    public function show(Cart $cart): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/{cartRef}/edit', name: 'app_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cart/edit.html.twig', [
            'cart' => $cart,
            'form' => $form,
        ]);
    }

    #[Route('/{cartRef}', name: 'app_cart_delete', methods: ['POST'])]
    public function delete(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getCartRef(), $request->request->get('_token'))) {
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/check-art', name: 'check_art', methods: ['POST'])]
    public function checkArt(Request $request): Response
    {
        // Retrieve the data from the request body
        $data = json_decode($request->getContent(), true);
        $uid = $data['uid'];
        $artRef = $data['art_ref'];
    
        // Check if the uid exists in the User entity
        $userExists = $this->getDoctrine()->getRepository(User::class)->find($uid);
    
        // Check if the artRef exists in the Cart entity
        $cartExists = $this->getDoctrine()->getRepository(Cart::class)->findOneBy(['art_ref' => $artRef]);
    
        // Check if both uid and artRef exist
        if ($userExists && $cartExists) {
            // Both uid and artRef exist
            return new Response('UID and artRef exist', Response::HTTP_OK);
        } else {
            // Either uid or artRef (or both) do not exist
            return new Response('Invalid UID or artRef', Response::HTTP_BAD_REQUEST);
        }
    }
    
}
