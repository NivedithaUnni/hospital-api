<?php

use Psr\Container\ContainerInterface;
require_once __DIR__ . '/../controllers/AppointmentController.php';
require_once __DIR__ . '/../controllers/DoctorController.php';

return function ($container) {

    $container->set(PDO::class, function () {

        return new PDO(
            "mysql:host=localhost;dbname=hospital_db",
            "root",
            ""
        );
    });

$container->set('PatientController', function ($container) {
    return new PatientController($container);
});

    $container->set('AppointmentController', function ($container) {
    return new AppointmentController($container);
});
   $container->set('DoctorController', function ($container) {
    return new DoctorController($container);
});

};