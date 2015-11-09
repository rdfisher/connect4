<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new \Connect4\Command\Server());
$application->add(new \Connect4\Command\Client());
$application->run();