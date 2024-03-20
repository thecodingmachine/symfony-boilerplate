<?php

declare(strict_types=1);

namespace App\Authenticator;

use App\Entity\User;
use App\Exception\Authenticator\SsoConsumerAuthNException;
use App\Exception\Authenticator\SsoConsumerException;
use OneLogin\Saml2\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\HttpUtils;

class Saml2Authenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    /** @param UserProviderInterface<User> $userProvider */
    public function __construct(
        private readonly UserProviderInterface $userProvider,
        private readonly HttpUtils $httpUtils,
        private readonly string $checkPath,
        private readonly Auth $auth,
        private readonly string $webAppUrl,
        private readonly UrlGeneratorInterface $router,
    ) {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool|null
    {
        return $this->httpUtils->checkRequestPath($request, $this->checkPath);
    }

    public function authenticate(Request $request): Passport
    {
        $session = $request->getSession();
        $authNRequestId = $session->get('AuthNRequestID');
        if (! \is_string($authNRequestId)) {
            throw new SsoConsumerAuthNException();
        }

        $auth = $this->auth;
        $auth->setStrict(false);
        $auth->processResponse($authNRequestId);
        $errors = $auth->getErrors();
        if (! empty($errors)) {
            throw new SsoConsumerException($auth);
        }
        $email = $auth->getNameId();
        $emails = $auth->getAttribute('email');

        if ($emails) {
            $email = $emails[0];
        }
        $this->userProvider->loadUserByIdentifier($email); // Check user existence

        return new SelfValidatingPassport(new UserBadge($email));
    }

    /** This is kept as example */
    public function basicAuthenticate(Request $request): Passport
    {
        $data = \json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $username = $data['username'];

        $this->userProvider->loadUserByIdentifier($username);

        return new SelfValidatingPassport(new UserBadge($username));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response|null
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response|null
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /** @inheritDoc */
    public function start(Request $request, AuthenticationException|null $authException = null)
    {
        $referer = (string) $request->headers->get('referer');
        $finalReturnTo = $this->webAppUrl;
        if (str_starts_with($referer, $this->webAppUrl)) {
            $finalReturnTo = $referer;
        }

        $returnTo = $this->router->generate('api_login_saml2', ['returnTo' => $finalReturnTo], UrlGeneratorInterface::ABSOLUTE_URL);
        $session = $request->getSession();
        $auth = $this->auth;
        $url = $auth->login($returnTo, [], false, false, true);
        $authNRequestId = $auth->getLastRequestID();
        $session->set('AuthNRequestID', $authNRequestId);

        return new JsonResponse(['url' => $url], Response::HTTP_UNAUTHORIZED);
    }
}
