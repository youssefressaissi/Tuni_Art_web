<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
    }

    /**
     * Should this authenticator be used for this request?
     *
     * @param Request $request
     * @return bool|void
     */
    public function supports(Request $request) : bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
    }



    /**
     * Gather the credentials which need to be checked in order to authenticate.
     *
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request) # FIRST
    {
        // Log debug information
        $this->logger->debug('getCredentials: Starting process...');

        $credentials = [
            'email'       => $request->request->get('email'),
            'password'    => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(Security::LAST_USERNAME, $credentials['email']);

        return $credentials;
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * @param array $credentials
     * @param UserProviderInterface $userProvider
     * @return object|UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider) # SECOND
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
    }

    private function isPasswordHashed($password)
    {
        // Check if the password starts with the $ character
        return substr($password, 0, 1) === '$';
    }


    /**
     * Check credentials
     *
     * Check csrf token is valid
     * Check password is valid
     *
     * @param array $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // Log debug information
        $this->logger->debug('checkCredentials: Starting process...');

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        // if (!$this->csrfTokenManager->isTokenValid($token)) {
        //     throw new InvalidCsrfTokenException();
        // }

        // If the user is null, it means the email doesn't exist
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Email does not exist.');
        }

        // Check if the user's password is hashed
        if ($this->isPasswordHashed($user->getPassword())) {
            // Use the password encoder for hashed passwords
            if (!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
                throw new CustomUserMessageAuthenticationException('Invalid password.');
            }
        } else {
            // Compare the provided password directly for unhashed passwords
            if ($user->getPassword() != $credentials['password']) {
                throw new CustomUserMessageAuthenticationException('Invalid password.');
            }
        }

        return true;
    }



    public function authenticate(Request $request): Passport
    {
        // Log debug information
        $this->logger->debug('Authenticator: Starting authentication process...');

        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        // // 1. Try to redirect the user to their original intended path
        // if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {

        //     return new RedirectResponse($targetPath);
        // }

        // // 2. If admin...redirect to admin dashboard
        // if ($token->getUser()->isAdmin()) {
        //     return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
        // }

        // 3. If not, redirect to homepage
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
