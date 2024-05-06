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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Routing\RouterInterface;

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

    public function viewPortfolio(User $user)
    {
        $portfolioPath = 'assets/portfolios/' . $user->getPortfolio();
        $response = new BinaryFileResponse($portfolioPath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $user->getPortfolio()
        );

        return $response;
    }


    #[Route('/users', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();
        $users = $userRepository->findAll();

        // Filter out users with the role 'Admin'
        $usersWithoutAdmins = array_filter($users, function ($user) {
            return $user->getRole() !== 'Admin' && $user->getStatus() == 1;
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
    public function show(User $user, EntityManagerInterface $entityManager, UserRepository $userRepository, Security $security): Response
    {

        // Increment profileViews count
        $profileViews = $user->getProfileViews() + 1;
        $user->setProfileViews($profileViews);

        $followersCount = $user->getFollowers()->count();
        $followingCount = $user->getFollowing()->count();

        // Retrieve the chosen user from the database
        $chosenUser = $userRepository->findOneBy(['uid' => $user->getUid()]);

        $isFollowing = false;
        if ($security->getUser()) {
            // Retrieve the logged-in user
            $email = $this->getUser()->getUserIdentifier();
            $loggedInUser = $userRepository->findOneBy(['email' => $email]);

            // Check if the logged-in user is following the chosen user
            if ($loggedInUser) {
                $followers = $loggedInUser->getFollowing();
                foreach ($followers as $follower) {
                    if ($follower->getFollowing() === $chosenUser) {
                        $isFollowing = true;
                        break;
                    }
                }
            }
        }

        // Persist changes to the database
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'isFollowing' => $isFollowing,
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
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $form = $this->createForm(UpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword !== null) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $plainPassword
                    )
                );
            }

            $newPhoneNb = $form->get('phoneNb')->getData();
            if ($newPhoneNb !== $user->getPhoneNb()) {
                // Update the phone number only if it has changed
                $user->setPhoneNb($newPhoneNb);
            }

            $newEmail = $form->get('email')->getData();
            if ($newEmail !== $user->getEmail()) {
                // Update the phone number only if it has changed
                $user->setEmail($newEmail);
            }

            $imageFile = $form->get('profilePic')->getData();
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
                    return $this->redirectToRoute('app_user_edit');
                }

                $user->setProfilePic($newFilename);
            }

            $portfolioFile = $form->get('portfolio')->getData();
            if ($portfolioFile) {
                $originalFilename1 = pathinfo($portfolioFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename1 = $slugger->slug($originalFilename1);
                $newFilename1 = $safeFilename1 . '-' . uniqid() . '.' . $portfolioFile->guessExtension();

                try {
                    $portfolioFile->move(
                        $this->getParameter('portfolios_directory'), // Assuming you have configured this parameter
                        $newFilename1
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    $this->addFlash('error', 'An error occurred while uploading the portfolio document.');
                    return $this->redirectToRoute('app_user_edit'); // Replace with your actual route name
                }

                $user->setPortfolio($newFilename1);

                // Check if biography is not null and portfolio is uploaded, then set role to Artist
                if ($form->get('biography')->getData() !== null && $portfolioFile) {
                    $user->setRole('Artist');
                    $this->sendUpdateRoleEmail($user, $mailer);
                }
            }


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

    #[Route('deactivate/{uid}', name: 'app_user_deactivate', methods: ['GET', 'POST'])]
    public function deactivate(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        $user->setStatus(0);
        $user->setDeactivate('TEMP');
        $entityManager->flush();

        return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('unauthorized/{uid}', name: 'app_unauthorized', methods: ['GET'])]
    public function unauthorized(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('security/unauthorized.html.twig', [
            'user' => $user,
        ]);
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
        return $this->redirectToRoute('app_user_show', ['uid' => $userToFollow->getUid()]);
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
        return $this->redirectToRoute('app_user_show', ['uid' => $userToUnfollow->getUid()]);
    }

    private function sendUpdateRoleEmail(User $user, MailerInterface $mailer,)
    {

        $transport = Transport::fromDsn('smtp://skander.kechaou.e@gmail.com:syqckzzljomspuzp@smtp.gmail.com:587');
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from('skander.kechaou.e@gmail.com')
            ->to($user->getEmail())
            ->subject('Artist Status')
            ->html(sprintf(
                'Hello %s, <br><br> We are glad to have you now as an <strong>Artist</strong>. <br><br> Best regards, <br> Tuni-Art',
                $user->getFname() . ' ' . $user->getLname(), // Adjust this according to your user entity
            ));
        $mailer->send($email);
    }

    #[Route("/search", name: 'user_search')]
    public function search(Request $request, UserRepository $userRepository): Response
    {
        // Get search query and selected criteria from the request
        $searchQuery = $request->query->get('search_query');
        $criteria = $request->query->get('criterias');

        // Initialize criteria array
        $criteriaArray = [];

        // Add search query to criteria array based on selected criteria
        if ($criteria === 'fname') {
            $criteriaArray['fname'] = $searchQuery;
        } elseif ($criteria === 'lname') {
            $criteriaArray['lname'] = $searchQuery;
        } elseif ($criteria === 'role') {
            $criteriaArray['role'] = $searchQuery;
        }

        // Retrieve arts based on criteria
        $user = $userRepository->findByCriteria($criteriaArray);
        $usersWithoutAdmins = array_filter($user, function ($user) {
            return $user->getRole() !== 'Admin' && $user->getStatus() == 1;
        });

        // Optionally, perform additional processing on retrieved arts if needed

        return $this->render('user/index.html.twig', [
            'usersWithoutAdmins' => $usersWithoutAdmins,
        ]);
    }

    #[Route('/sort', name: 'app_user_sort', methods: ['GET'])]
    public function sortedList(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $sortBy = $request->query->get('sort_by');

        switch ($sortBy) {
            case 'role_desc':
                $sortBy = ['role' => 'DESC'];
                break;
            case 'views_desc':
                $sortBy = ['profileviews' => 'DESC'];
                break;
            case 'name_desc':
                $sortBy = ['fname' => 'ASC'];
                break;
            default:
                $sortBy = ['profileviews' => 'DESC'];
                break;
        }

        $user = $userRepository->findByCriteria([], $sortBy);
        $usersWithoutAdmins = array_filter($user, function ($user) {
            return $user->getRole() !== 'Admin' && $user->getStatus() == 1;
        });

        $userViews = [];
        foreach ($user as $userItem) {
            $userViews[] = $userItem->getProfileViews();
        }

        $stats = $this->calculateUserStatistics($userViews);

        return $this->render('user/index.html.twig', [
            'usersWithoutAdmins' => $usersWithoutAdmins,
            'stats' => $stats,
        ]);
    }

    private function calculateUserStatistics(array $userViews): array
    {
        // You can implement the logic to calculate statistics here
        // For example, calculate average, maximum, minimum, etc.
        $averageViews = array_sum($userViews) / count($userViews);
        $maxViews = max($userViews);
        $minViews = min($userViews);

        return [
            'average_views' => $averageViews,
            'max_views' => $maxViews,
            'min_views' => $minViews,
        ];
    }

    #[Route('/generatePDF/{uid}', name: 'app_generate_pdf', methods: ['GET'])]
    public function generatePDF(User $user, RouterInterface $router): Response
    {
        // Construct the filename
        $filename = 'user' . $user->getUid() . $user->getFname() . $user->getLname() . '_details.pdf';
        $filename = preg_replace('/\s+/', '', $filename); // Remove any whitespace

        // Create PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // HTML content for PDF
        $html = $this->renderView('user/pdf_template.html.twig', [
            'user' => $user,
        ]);

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Get the PDF content
        $pdfContent = $dompdf->output();

        // Create the response object
        $response = new Response($pdfContent);

        // Set headers for PDF file to make it downloadable
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Return the response with the PDF content
        return $response;
    }
}
