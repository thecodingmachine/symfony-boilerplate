<?php

declare(strict_types=1);

namespace App\UseCase\Company;

use App\Dto\Request\Company\CompanyRequestDto;
use App\Entity\Company;
use App\UseCase\Storage\StoreFile;

class UpdateCompany
{
    public function __construct(private readonly StoreFile $storeFile)
    {
    }

    public function update(Company $company, CompanyRequestDto $companyRequestDto): Company
    {
        $company->setName($companyRequestDto->getName());
        if ($companyRequestDto->getIndentityFile() !== null) {
            $identityFile = $this->storeFile->storeUploadedCompanyIdentityFile($companyRequestDto->getIndentityFile(), $company);
            $company->setIndentityFile($identityFile);
        }

        return $company;
    }
}
