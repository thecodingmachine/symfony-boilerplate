<?php

declare(strict_types=1);

namespace App\UseCase\Company;

use App\Dto\Request\Company\CompanyRequestDto;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class CreateCompany
{
    public function __construct(
        private readonly UpdateCompany $updateCompany,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function create(CompanyRequestDto $companyRequestDto): Company
    {
        $company = new Company();
        $company = $this->updateCompany->update($company, $companyRequestDto);
        $this->entityManager->persist($company);

        return $company;
    }
}
