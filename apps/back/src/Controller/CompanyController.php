<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\Company\CompanyRequestDto;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\UseCase\Company\CreateCompany;
use App\UseCase\Company\UpdateCompany;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CompanyController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyRepository $companyRepository,
        private readonly CreateCompany $createCompany,
        private readonly UpdateCompany $updateCompany,
    ) {
    }

    #[Route('/companies', name: 'list_companies', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $companies = $this->companyRepository->findAll();

        return $this->json($companies, 200, [], ['groups' => Company::GROUP_LIST]);
    }

    #[Route('/companies/{id}', name: 'get_company', methods: ['GET'])]
    #[IsGranted('ROLE_RIGHT_COMPANY_READ')]
    public function get(Company $company): JsonResponse
    {
        return $this->json($company, 200, [], ['groups' => Company::GROUP_DETAILS]);
    }

    #[Route('/companies', name: 'create_company', methods: ['POST'])]
    #[IsGranted('ROLE_RIGHT_COMPANY_CREATE')]
    public function create(#[MapRequestPayload] CompanyRequestDto $companyRequestDto, Request $request): JsonResponse
    {
        $identityFile = $request->files->get('identityFile');
        if ($identityFile !== null) {
            \assert($identityFile instanceof UploadedFile);
            $companyRequestDto->setIndentityFile($identityFile);
        }
        $company = $this->createCompany->create($companyRequestDto);
        $this->entityManager->flush();

        return $this->json($company, 200, [], ['groups' => Company::GROUP_DETAILS]);
    }

    #[Route('/companies/{id}', name: 'update_company', methods: ['POST'])]
    #[IsGranted('ROLE_RIGHT_COMPANY_UPDATE')]
    public function update(Company $company, #[MapRequestPayload] CompanyRequestDto $companyRequestDto, Request $request): JsonResponse
    {
        $identityFile = $request->files->get('identityFile');
        if ($identityFile !== null) {
            \assert($identityFile instanceof UploadedFile);
            $companyRequestDto->setIndentityFile($identityFile);
        }
        $company = $this->updateCompany->update($company, $companyRequestDto);
        $this->entityManager->flush();

        return $this->json($company, 200, [], ['groups' => Company::GROUP_DETAILS]);
    }
}
