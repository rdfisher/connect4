<?php
require_once __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('UTC');

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new \Connect4\Command\Server(realpath(__DIR__ . '/../web')));
$application->add(new \Connect4\Command\Client());
$application->run();