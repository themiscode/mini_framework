<?php

use App\Core\Application;

require '../vendor/autoload.php';

$app = Application::getInstance();

echo $app->start();
