<?php

namespace Controllers;

use Service\UserService;
use Firebase\JWT\JWT;
use Model\User;
use Exception;

class UserController extends Controller {

    function __construct(
        private $service = new UserService()
    ) {}

    public function login(): mixed {
        $postedUser = $this->createObjectFromPostedJson("Model\User");

        $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);
        if(!$user) {
            $this->respondWithError(401, "Invalid login");
        }

        $tokenResponse = $this->generateJwt($user);       

        $this->respond($tokenResponse);    
    }

    public function generateJwt(User $user): array {
        $secret_key = SECRET_KEY;
        
        // JWT expiration times should be kept short (10-30 minutes)
        // A refresh token system should be implemented if we want clients to stay logged in for longer periods
        
        // note how these claims are 3 characters long to keep the JWT as small as possible
        $issuedAt = time(); // issued at
        $payload = array(
            "iss" => DOMAIN, // this can be the domain/servername that issues the token
            "aud" => DOMAIN, // this can be the domain/servername that checks the token
            "iat" => $issuedAt,
            "nbf" => $issuedAt, //not valid before 
            "exp" => ($issuedAt + 1800), // expiration time is set at +600 seconds (10 minutes)
            "data" => array(
                "id" => $user->id,
                "name" => $user->name,
                "user_roll" => $user->user_roll
        ));

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "username" => $user->name,
                "expireAt" => ($issuedAt + 1800)
            );
    }

    public function create(): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $user = $this->createObjectFromPostedJson("Models\\User");
            $user = $this->service->create($user);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($user);
    }

    public function getById(int $id): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        $user = $this->service->getById($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$user) {
            $this->respondWithError(404, "Product not found");
        }

        $this->respond($user);
    }
}