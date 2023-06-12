<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController
{
    #[Route('/healthcheck', name: 'app_health_check')]
    public function index(): JsonResponse
    {
        return new JsonResponse(['success' => true]);
    }

    #[Route('/healthcheck/logged', name: 'app_health_check_logged')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function logged(): JsonResponse
    {
        return new JsonResponse(['success' => true]);
    }
}
