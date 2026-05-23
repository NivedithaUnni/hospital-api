<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

// Controllers
require_once __DIR__ . '/../src/controllers/PatientController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/AppointmentController.php';

$container = new Container();

//  LOAD FIRST (VERY IMPORTANT)
(require __DIR__ . '/../src/config/db.php')($container);

//  THEN SET CONTAINER
AppFactory::setContainer($container);

// THEN CREATE APP
$app = AppFactory::create();

// Middleware
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Routes
(require __DIR__ . '/../src/routes/routes.php')($app);

$app->run();