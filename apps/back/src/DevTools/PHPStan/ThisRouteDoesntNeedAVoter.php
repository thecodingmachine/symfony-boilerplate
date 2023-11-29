<?php

declare(strict_types=1);

namespace App\DevTools\PHPStan;

use Attribute;

/**
 * Add this attribute if the Route is limited to some users with specific permissions, but no horizontal check is needed
 *  - no "ownership"
 *  - no user context should allow / deny user from accessing the underlying resources
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ThisRouteDoesntNeedAVoter
{
}
