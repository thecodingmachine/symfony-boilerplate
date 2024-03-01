<?php

declare(strict_types=1);

namespace App\Authenticator;

use App\Exception\Authenticator\UnexpectedValueException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class LoginFormAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private string $webAppLoginUrl,
        private string $webAppUrl,
    ) {
    }

    private function isWebappUrl(string $url): bool
    {
        return str_starts_with($url, $this->webAppUrl);
    }

    private function extractReturnTo(string $referer): string
    {
        $refererParts = parse_url($referer);
        $refererParams = [];
        parse_str($refererParts['query'] ?? '', $refererParams);
        if (\array_key_exists('returnTo', $refererParams) && $refererParams['returnTo'] && \is_string($refererParams['returnTo'])) {
            return $refererParams['returnTo'];
        }

        return $referer;
    }

    private function safeExtractReturnTo(string $referer): string
    {
        $extractedReturnTo = $this->extractReturnTo($referer);
        if (!$this->isWebappUrl($extractedReturnTo)) {
            return '';
        }

        return $extractedReturnTo;
    }

    public function start(Request $request, AuthenticationException|null $authException = null): JsonResponse
    {
        $referer = (string) $request->headers->get('referer');
        if (!$referer) {
            return new JsonResponse(['url' => $this->webAppLoginUrl], 401);
        }
        $returnTo = $this->safeExtractReturnTo($referer);
        if (!$returnTo) {
            $returnTo = $this->webAppUrl;
        }
        $urlParts = parse_url($this->webAppLoginUrl);
        $existingParams = [];
        parse_str($urlParts['query'] ?? '', $existingParams);
        $params = array_merge(['returnTo' => $returnTo], $existingParams);
        $queryString = http_build_query($params);
        if (!$urlParts) {
            throw new UnexpectedValueException('unexpected webAppLoginUrl');
        }
        $urlWithParams = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . $queryString;

        return new JsonResponse(['url' => $urlWithParams], 401);
    }
}
