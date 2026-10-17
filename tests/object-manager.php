<?php

declare(strict_types=1);

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

new Dotenv()->bootEnv(__DIR__ . '/../.env');

$env = $_SERVER['APP_ENV'];
if (!is_string($env)) {
    throw new RuntimeException('APP_ENV must be a string');
}

$kernel = new Kernel($env, filter_var($_SERVER['APP_DEBUG'], \FILTER_VALIDATE_BOOL));
$kernel->boot();

return $kernel->getContainer()->get('doctrine')->getManager();
