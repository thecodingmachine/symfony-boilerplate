<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

abstract class DownloadController extends AbstractController
{
    private function createResponse(string $filename, string $fileContent, string $disposition): Response
    {
        $response          = new Response($fileContent);
        $dispositionHeader = $response->headers->makeDisposition(
            disposition: $disposition,
            filename   : $filename
        );
        $response->headers->set(key: 'Content-Disposition', values: $dispositionHeader);

        return $response;
    }

    protected function createResponseWithAttachment(string $filename, string $fileContent): Response
    {
        return $this->createResponse(
            filename   : $filename,
            fileContent: $fileContent,
            disposition: ResponseHeaderBag::DISPOSITION_ATTACHMENT
        );
    }

    protected function createResponseInline(string $filename, string $fileContent): Response
    {
        return $this->createResponse(
            filename   : $filename,
            fileContent: $fileContent,
            disposition: ResponseHeaderBag::DISPOSITION_INLINE
        );
    }
}
