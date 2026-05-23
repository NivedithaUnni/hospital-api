<?php

use DI\Container;

// REQUIRE ALL CONTROLLERS
require_once __DIR__ . '/../controllers/PatientController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AppointmentController.php'; // 🔥 ADD THIS

return function (Container $container) {

    //  DATABASE
    $container->set(PDO::class, function () {
        return new PDO(
            "mysql:host=localhost;dbname=hospital_db",
            "root",
            ""
        );
    });

    // ========================
    // 🔥 CONTROLLERS
    // ========================

    $container->set('PatientController', function ($container) {
        return new PatientController($container);
    });

    $container->set('AuthController', function ($container) {
        return new AuthController();
    });

    // 🔥 ADD THIS (YOU MISSED THIS BEFORE)
    $container->set('AppointmentController', function ($container) {
        return new AppointmentController($container);
    });

};