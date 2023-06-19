<?php

namespace Controllers;

use Services\UserService;
use Firebase\JWT\JWT;
use Models\User;
use Exception;

class UserController extends Controller {

    private $service;
    
    function __construct() {
        $this->service = new UserService();
    }

    public function login() {
        $postedUser = $this->createObjectFromPostedJson("Models\\User");
        
        $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);
        if(!$user) {
            $this->respondWithError(401, "Invalid login");
        }

        // $tokenResponse = $this->generateJwt($user);       
        $tokenResponse = "$postedUser->username, $postedUser->password";       

        $this->respond($tokenResponse);    
    }

    public function generateJwt(User $user): array {
        $secret_key = 'thisisasecretkey';
        $domain = 'http://localhost//WebDevelopment2/';

        $issuedAt = time();
        $payload = array(
            "iss" => $domain, 
            "aud" => $domain, 
            "iat" => $issuedAt,
            "nbf" => $issuedAt, 
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
            ]);
        } catch(Exception $e) {
            return new Exception("405: Unauthorized message.");
        }
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

        $user = $this->service->getUser($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$user) $this->respondWithError(404, "Product not found");

        $this->respond($user);
    }
}