<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Process\Process;

class GraphQlTest extends WebTestCase
{
    public function testSchemaHaveToBeGenerated(): void
    {
        $callSchema = new Process(['php', 'bin/console', 'graphqlite:dump-schema']);
        $callSchema->run();
        $std      = $callSchema->getOutput();
        $stdError = $callSchema->getErrorOutput();
        $this->assertSame(0, $callSchema->getExitCode(), $stdError);
        $this->assertNotSame('', $std, 'No schema');
    }
}
