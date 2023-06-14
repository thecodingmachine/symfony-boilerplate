<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

$processes = [
    new Process(['php', 'bin/console', '--env=test', 'doctrine:database:create']),
    new Process(['php', 'bin/console', '--env=test', 'doctrine:schema:create']),
    new Process(['php', 'bin/console', '--env=test', 'doctrine:fixtures:load']),
];

foreach ($processes as $process) {
    $process->run();
}


