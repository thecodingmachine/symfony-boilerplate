<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PaymentRepository;
use App\Repository\UserRepository;
use App\Service\GooglePlacesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlacesController extends AbstractController
{
    private $googlePlacesService;

    public function __construct(
        GooglePlacesService $googlePlacesService,
        private readonly EntityManagerInterface $entityManager,
        private readonly PaymentRepository $paymentRepository,
        private readonly UserRepository $userRepository,
    ) {
        $this->googlePlacesService = $googlePlacesService;
    }

    #[Route('/nearby-places', name: 'nearby_places', methods: ['GET'])]
    public function nearbyPlaces(Request $request): JsonResponse
    {
        $lat = (float) $request->query->get('lat');
        $lng = (float) $request->query->get('lng');
        $results = $this->googlePlacesService->searchPlaces($lat, $lng);

        return $this->json(['results' => $results]);
    }

    #[Route('/place/{paymentId}', name: 'api_identify_place', methods: ['POST'])]
    public function idenitfyPlace(Request $request, int $paymentId): JsonResponse
    {
        $loggedUser = $this->getUser();

        if (!$loggedUser) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        $payment = $this->paymentRepository->find($paymentId);

        if (!$payment) {
            throw new NotFoundHttpException('Payment not found.');
        }

        if ($payment->getUser() !== $loggedUser) {
            throw new AccessDeniedHttpException('You do not have permission to update this payment.');
        }

        $data = json_decode($request->getContent(), true);

        // Create place
        $place = new Place();

        if (isset($data['newPlaceName'])) {
            $place->setName($data['newPlaceName']);
        }

        if (isset($data['newPlaceAddress'])) {
            $place->setAdress($data['newPlaceAddress']);
        }

        if (isset($data['newPlaceGpsPosition'])) {
            $place->setGpsPosition($data['newPlaceGpsPosition']);
        }

        $place->setCreatedAt(new \DateTimeImmutable());
        $place->setModifiedAt(new \DateTimeImmutable());

        // Update payment: set place
        $payment->setPlace($place);

        // Calculate the score
        $totalIdentifications = $this->userRepository->countIdentifications($loggedUser);

        if ($totalIdentifications < 10) {
            $scoreToAdd = 5;
        } elseif ($totalIdentifications < 30) {
            $scoreToAdd = 3;
        } else {
            $scoreToAdd = 1;
        }

        // Check for unique payment label
        $countOfOtherUsersPaymentsWithSameLabel = $this->paymentRepository->countPaymentsByLabelExcludingUser($payment->getLabel());
        if ($countOfOtherUsersPaymentsWithSameLabel === 0) {
            $scoreToAdd = 20;
        }

        // Update the user's score
        $loggedUser->addScore($scoreToAdd);
        $this->entityManager->persist($place);
        $this->entityManager->persist($loggedUser);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Payment updated successfully', 'scoreAdded' => $scoreToAdd]);
    }
}
