<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;

class AuthController
{
    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if ($email === 'admin@gmail.com' && $password === '1234') {

            $payload = [
                "user_id" => 1,
                "email" => $email,
                "role" => "admin",
                "exp" => time() + 3600
            ];

            $token = JWT::encode(
                $payload,
                "THIS_IS_MY_SECURE_SECRET_KEY_2026_HOSPITAL_API",
                'HS256'
            );

            $response->getBody()->write(json_encode([
                "token" => $token
            ]));

            return $response->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            "error" => "Invalid credentials"
        ]));

        return $response->withStatus(401)
                        ->withHeader('Content-Type', 'application/json');
    }
}