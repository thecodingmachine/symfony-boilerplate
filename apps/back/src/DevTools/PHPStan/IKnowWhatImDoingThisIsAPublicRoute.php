<?php

declare(strict_types=1);

namespace App\DevTools\PHPStan;

use Attribute;

/**
 * Add this attribute to Routes that can be accessed by ANY ONE
 * This is generally the case for
 *   - public website / content
 *   - Login / Password recovery
 *   - If you are securing your route elsewhere (firewall, security.yml, custom function, etc.)
 */
#[Attribute(Attribute::TARGET_METHOD)]
class IKnowWhatImDoingThisIsAPublicRoute
{
}
