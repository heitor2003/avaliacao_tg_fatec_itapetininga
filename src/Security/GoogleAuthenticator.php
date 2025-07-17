<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class GoogleAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private $clientRegistry;
    private $entityManager;
    private $urlGenerator;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'knpu_oauth2_client_connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $client->getAccessToken();

        $googleUser = $client->fetchUserFromToken($accessToken);

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $fullName = $googleUser->getName(); 

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleId]);

        if (!$user) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $user = new User();
                $user->setEmail($email);
                $user->setFullName($fullName);
                $user->setPassword(password_hash(uniqid(), PASSWORD_DEFAULT));
                $user->setRoles(['ROLE_USER']);
                $user->setGoogleId($googleId);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } else {
                $user->setGoogleId($googleId);
                $this->entityManager->flush();
            }
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), function() use ($user) {
            return $user;
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->urlGenerator->generate('app_dashboard'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        $request->getSession()->getFlashBag()->add('error', 'Login Google falhou: ' . $message);

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $url = $this->urlGenerator->generate('app_login');

        return new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    }
}