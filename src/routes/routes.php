<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Middleware\AuthMiddleware;

require_once __DIR__ . '/../middleware/AuthMiddleware.php';

return function ($app) {

    //  PUBLIC ROUTES
    $app->group('/api', function ($group) {
        $group->post('/login', 'AuthController:login');
    });

    //  PROTECTED ROUTES
    $app->group('/api', function ($group) {

        $group->group('/patients', function ($group) {

            $group->get('', 'PatientController:getAll');
            $group->post('', 'PatientController:add');
            $group->get('/{id}', 'PatientController:getOne');
            $group->put('/{id}', 'PatientController:update');
            $group->delete('/{id}', 'PatientController:delete');

        });
        $group->group('/appointments', function ($group) {

    $group->post('', 'AppointmentController:create');
    $group->get('', 'AppointmentController:getAll');
    $group->get('/{id}', 'AppointmentController:getOne');
    $group->put('/{id}', 'AppointmentController:update');
    $group->put('/{id}/status', 'AppointmentController:updateStatus');
    $group->delete('/{id}', 'AppointmentController:delete');

});
    $group->group('/doctors', function ($group) {

    $group->get('', 'DoctorController:getAll');
    $group->post('', 'DoctorController:add');
    $group->put('/{id}', 'DoctorController:update');
    $group->delete('/{id}', 'DoctorController:delete');

});

    })->add(new AuthMiddleware()); //  correct place

};