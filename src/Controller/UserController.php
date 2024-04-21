<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\UpdateType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
        
    }

    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function home(UserRepository $userRepository): Response
    {
        return $this->render('index.html.twig');
        
    }

    #[Route('/signup', name: 'app_user_signup', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setRole('User');
        $user->setProfileviews(0);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            // return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            // Redirect to the show route with the newly created user's uid
            return $this->redirectToRoute('app_user_show', ['uid' => $user->getUid()]);
        }

        return $this->renderForm('user/signup.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // #[Route('/newthing', name: 'app_user_login', methods: ['GET', 'POST'])]
    // public function toLogin(Request $request): Response
    // {
    //   // No need for user object or form creation here
    //   dump($request->attributes->all()); // Debug statement
    
    //   return $this->render('user/login.html.twig');
    // }


    // Assuming the necessary imports are included

    #[Route('/newthing', name: 'app_user_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $email = $data['email'];
            $password = $data['password'];

            // Find user by email
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                // Add form error for invalid email
                $form->get('email')->addError(new FormError('Invalid email address.'));
                return $this->render('user/login.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Check if password is correct
            if ($passwordEncoder->isPasswordValid($user, $password)) {
                // Password is correct, perform login
                // For example, you can set a session variable or use Symfony's built-in authentication mechanism
                // Here, for simplicity, I'm just redirecting to another page
                return $this->redirectToRoute('app_user_home');
            } else {
                // Add form error for invalid password
                $form->get('password')->addError(new FormError('Invalid password.'));
                return $this->render('user/login.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        // Return the initial login form
        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{uid}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{uid}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getUid(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    

}
