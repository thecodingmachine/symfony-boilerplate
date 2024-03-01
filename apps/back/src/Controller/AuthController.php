<?php

declare(strict_types=1);

namespace App\Controller;

use App\DevTools\PHPStan\IKnowWhatImDoingThisIsAPublicRoute;
use App\DevTools\PHPStan\ThisRouteDoesntNeedAVoter;
use App\Entity\User;
use OneLogin\Saml2\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly Auth $auth,
        private readonly string $appUrl,
    ) {
    }

    #[IKnowWhatImDoingThisIsAPublicRoute]
    #[Route('/auth/sso/saml2/login', name: 'api_login_saml2', methods: ['POST'])]
    public function samlLogin(): Response
    {
        // Redirect to the frontend
        return new RedirectResponse($this->appUrl);
    }

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    #[IKnowWhatImDoingThisIsAPublicRoute]
    public function login(#[CurrentUser]
    User|null $user,): JsonResponse
    {
        return new JsonResponse($user);
    }

    #[Route('/auth/me', name: 'api_me')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[ThisRouteDoesntNeedAVoter]
    public function getLoggedUser(#[CurrentUser]
    User $user,): JsonResponse
    {
        return new JsonResponse($user);
    }

    #[Route('/auth/sso/saml2/metadata', name: 'api_get_auth_sso_saml2_metadata')]
    #[IKnowWhatImDoingThisIsAPublicRoute]
    public function getMetadata(): Response
    {
        $auth = $this->auth;
        $settings = $auth->getSettings();
        $metadata = $settings->getSPMetadata(true);

        return new Response(content: $metadata, headers: ['content-type' => 'xml']);
    }
}
