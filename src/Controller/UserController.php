<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\UpdateType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{

    public function showImage($filename)
    {
        $filePath = '/assets/images/profilepics/' . $filename; // Path to your image file

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


    #[Route('/users', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();
        $users = $userRepository->findAll();

        // Filter out users with the role 'Admin'
        $usersWithoutAdmins = array_filter($users, function ($user) {
            return $user->getRole() !== 'Admin';
        });

        return $this->render('user/index.html.twig', [
            'usersWithoutAdmins' => $usersWithoutAdmins,
            'user' => $user,
        ]);
    }

    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function home(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('index.html.twig', [
            'user' => $user,
        ]);
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

    #[Route('user/{uid}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, EntityManagerInterface $entityManager): Response
    {
        // Increment profileViews count
        $profileViews = $user->getProfileViews() + 1;
        $user->setProfileViews($profileViews);

        $followersCount = $user->getFollowers()->count();
        $followingCount = $user->getFollowing()->count();

        // Persist changes to the database
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
        ]);
    }

    #[Route('profile/{uid}', name: 'app_profile', methods: ['GET'])]
    public function profile(User $user, Security $security): Response
    {
        // // Get the currently authenticated user
        // $user = $security->getUser();

        $followersCount = $user->getFollowers()->count();
        $followingCount = $user->getFollowing()->count();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
        ]);
    }

    #[Route('user/{uid}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
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

    #[Route('user/{uid}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getUid(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{uid}/qr-code', name: 'app_user_qrcode')]
    public function generateQRCode(User $user): Response
    {
        $userGender = $user->getGender() ? 'Female' : 'Male';
        $qrCodeData = 'User Details:' . PHP_EOL;
        $qrCodeData .= 'User ID: ' . $user->getUid() . PHP_EOL;
        $qrCodeData .= 'Full Name: ' . $user->getFname() . ' ' . $user->getLname() . PHP_EOL;
        $qrCodeData .= 'Gender: ' . $userGender . PHP_EOL;
        $qrCodeData .= 'Role: ' . $user->getRole() . PHP_EOL;
        $qrCodeData .= 'Date of Birth: ' . $user->getBirthDate()->format('F j, Y') . PHP_EOL;
        $qrCodeData .= 'E-mail Address: ' . $user->getEmail() . PHP_EOL;
        $qrCodeData .= 'Phone Number: ' . $user->getPhoneNb() . PHP_EOL;
        $qrCodeData .= 'Profile Views: ' . $user->getProfileviews() . PHP_EOL;
        if ($user->getRole() == 'Artist') {
            $qrCodeData .= 'Biography: ' . $user->getBiography() . PHP_EOL;
        }
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
        return $this->renderForm('user/qrcode.html.twig', [
            'qrCode' => $dataUri,
            'user' => $user,
        ]);
    }

    #[Route('/follow/{uid}', name: 'app_follow', methods: ['GET'])]
    public function follow(User $userToFollow, Security $security, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Get the currently authenticated user
        $email = $security->getUser()->getUserIdentifier();
        $currentUser = $userRepository->findOneBy(['email' => $email]);

        // Follow the user
        $currentUser->addFollowing($userToFollow);

        // Persist changes to the database
        $entityManager->flush();

        // Redirect or render a response
        return $this->redirectToRoute('user_profile', ['uid' => $userToFollow->getUid()]);
    }

    #[Route('/unfollow/{uid}', name: 'app_unfollow', methods: ['GET'])]
    public function unfollow(User $userToUnfollow, Security $security,  UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Get the currently authenticated user
        $email = $security->getUser()->getUserIdentifier();
        $currentUser = $userRepository->findOneBy(['email' => $email]);

        // Unfollow the user
        $currentUser->removeFollowing($userToUnfollow);

        // Persist changes to the database
        $entityManager->flush();

        // Redirect or render a response
        return $this->redirectToRoute('user_profile', ['uid' => $userToUnfollow->getUid()]);
    }
}
