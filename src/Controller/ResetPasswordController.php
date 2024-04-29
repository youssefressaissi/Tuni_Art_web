<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\VerificationCodeGenerator;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ValidateResetCodeFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/reset-password/request', name: 'reset_password_request', methods: ['GET', 'POST'])]
    public function request(UserRepository $userRepository, Request $request, VerificationCodeGenerator $verficationCodeGenerator, MailerInterface $mailer, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        $user = new User();

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                // Start a session and store user information
                $session->set('user_email', $email);

                // Generate a unique code
                $resetCode = $verficationCodeGenerator->generateVerificationCode($user);

                if ($resetCode !== null) {
                    // Store the reset code 
                    $user->setVerificationCode($resetCode);

                    // Persist changes to the database
                    $entityManager->persist($user);
                    $entityManager->flush();


                    // Send email with reset code
                    $this->sendResetCodeEmail($user, $resetCode, $mailer);

                    // Redirect to code validation page
                    return $this->redirectToRoute('validate_reset_code', ['uid' => $user->getUid()]);
                }
            } else {
                // User not found, display an error message
                $this->addFlash('error', 'User with this email does not exist.');
            }
        }

        return $this->render('reset_password/request.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/reset-password/validate-code/{uid}', name: 'validate_reset_code', methods: ['GET', 'POST'])]
    public function validateCode(Request $request, UserRepository $userRepository, SessionInterface $session): Response
    {
        $form = $this->createForm(ValidateResetCodeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get('verificationCode')->getData();

            // Retrieve user's email from session
            $email = $session->get('user_email');

            // Fetch the user from the database using the email
            $user = $userRepository->findOneBy(['email' => $email]);

            // Get the stored reset code from database
            $resetCode = $user->getVerificationCode();

            if ($code === $resetCode) {
                // Redirect to password update page
                return $this->redirectToRoute('update_password', ['uid' => $user->getUid()]);
            } else {
                // Display an error message for invalid code
                $this->addFlash('error', 'Invalid reset code.');
            }
        }

        return $this->render('reset_password/validate_code.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/update-password/{uid}', name: 'update_password', methods: ['GET', 'POST'])]
    public function updatePassword(UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher, SessionInterface $session): Response
    {
        // Retrieve user's email from session
        $email = $session->get('user_email');
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if passwords match
            $plainPassword = $form->get('plainPassword')->getData();
            $plainPasswordRepeat = $form->get('plainPasswordRepeat')->getData();

            if ($plainPassword !== $plainPasswordRepeat) {
                $form->addError(new FormError('Passwords do not match.'));
                return $this->render('reset_password/update_password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Update user's password
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to success page
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    private function sendResetCodeEmail(User $user, string $resetCode, MailerInterface $mailer,)
    {

        $transport = Transport::fromDsn('smtp://skander.kechaou.e@gmail.com:syqckzzljomspuzp@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        //  $resetPasswordUrl = $urlGenerator->generate('app_reset_mypass', ['token' => $token, 'timestamp' => time()], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('skander.kechaou.e@gmail.com')
            ->to($user->getEmail())
            ->subject('Password Reset Request')
            ->html(sprintf(
                'Hello %s, <br><br> Here is your verification code: <strong>%s</strong>. <br><br> Best regards, <br> Tuni-Art',
                $user->getFname() . ' ' . $user->getLname(), // Adjust this according to your user entity
                $resetCode
            ));
        $mailer->send($email);
    }
}
