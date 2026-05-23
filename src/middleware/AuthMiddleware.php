<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware
{
    public function __invoke(Request $request, $handler): Response
    {
        //  Get Authorization header
        $authHeader = $request->getHeaderLine('Authorization');

        //  No token
        if (!$authHeader) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                "error" => "Token required"
            ]));

            return $response->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
        }

        // Extract token
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            //  Decode token
            $decoded = JWT::decode(
                $token,
                new Key("THIS_IS_MY_SECURE_SECRET_KEY_2026_HOSPITAL_API", 'HS256')
            );

            //  Attach user to request
            $request = $request->withAttribute('user', $decoded);
             $user = $decoded;
             
            // ============================
            //  ROLE CHECK (OPTIONAL)
            // ============================
            /*
            if ($decoded->role !== 'admin') {
                $response = new SlimResponse();
                $response->getBody()->write(json_encode([
                    "error" => "Access denied"
                ]));

                return $response->withStatus(403)
                                ->withHeader('Content-Type', 'application/json');
            }
            */

        } catch (\Exception $e) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                "error" => "Invalid or expired token"
            ]));

            return $response->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
        }

        //  Continue request
        return $handler->handle($request);
    }
}