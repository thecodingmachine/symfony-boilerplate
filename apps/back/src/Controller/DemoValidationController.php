<?php

declare(strict_types=1);

namespace App\Controller;

use App\DevTools\PHPStan\ThisRouteDoesntNeedAVoter;
use App\Dto\Request\Demo\CreateDemoValidationDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DemoValidationController extends AbstractController
{
    #[Route('/demo/validation', name: 'create_demo_validation', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[ThisRouteDoesntNeedAVoter]
    public function create(#[MapRequestPayload]
    CreateDemoValidationDto $createDemoValidationDto,): JsonResponse
    {
        return $this->json($createDemoValidationDto);
    }
}
