<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\Request\CreateUserDto;
use App\Dto\Request\UpdateUserDto;
use App\Entity\User;
use App\Entity\Payment;
use App\Repository\UserRepository;
use App\Repository\PaymentRepository;
use App\UseCase\User\CreateUser;
use App\UseCase\User\UpdateUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;


class PaymentController extends AbstractController
{    

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PaymentRepository $paymentRepository,
        private readonly UserRepository $userRepository,
    ) {
    }
    
    #[Route("/payments", name: 'api_get_user_payments', methods: ['GET'])]
    public function getPayments(): JsonResponse
    {
        $loggedUser = $this->getUser(); // Get the currently authenticated user
        
        if($loggedUser){
            $user_id = $loggedUser->getId();
            $user = $this->userRepository->find($user_id);
            if (!$user) {
                // Handle the case where the user is not found
                return new JsonResponse(['message' => 'User not authenticated'], 401);
            }

            // Fetch payments for the authenticated user
            $payments = $user->getPayments();

            // Convert the payments collection to an array
            $paymentsArray = $payments->toArray();

            // Convert entities to an array suitable for JSON encoding
            $data = array_map(function(Payment $payment) {
                return [
                    'id' => $payment->getId(),
                    'label' => $payment->getLabel(),
                    'amount' => $payment->getAmount(),
                    'location' => $payment->getLocation(),
                    'gps_position' => $payment->getGpsPosition(),
                    'created_at' => $payment->getCreatedAt()->format('Y-m-d H:i:s'),
                    'modified_at' => $payment->getModifiedAt()->format('Y-m-d H:i:s')
                ];
            }, $paymentsArray);

            return new JsonResponse($data);

    
        }else{
            return new JsonResponse(['message' => 'User not authenticated 2'], 401);
        }
    }

    #[Route("/payments/{paymentId}", name: 'api_update_user_payment', methods: ['PUT'])]
    public function updatePayment(Request $request, int $paymentId): JsonResponse
    {
        $loggedUser = $this->getUser();

        if (!$loggedUser) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        // Use $this->paymentRepository that we got from constructor injection
        $payment = $this->paymentRepository->find($paymentId);

        if (!$payment) {
            throw new NotFoundHttpException('Payment not found.');
        }

        if ($payment->getUser() !== $loggedUser) {
            throw new AccessDeniedHttpException('You do not have permission to update this payment.');
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['label'])) {
            $payment->setLabel($data['label']);
        }

        if (isset($data['amount'])) {
            $payment->setAmount($data['amount']);
        }

        if (isset($data['newPlaceName']) && isset($data['newPlaceAddress'])) {
            $new_location = $data['newPlaceName'] . ' ' . $data['newPlaceAddress'];
            $payment->setLocation($new_location);
        }

        $payment->setModifiedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Payment updated successfully']);
    }


}
