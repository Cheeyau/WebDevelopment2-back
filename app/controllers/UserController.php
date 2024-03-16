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
        
        $user = $this->service->checkUsernamePassword($postedUser->name, $postedUser->password);
        if(!$user) $this->respondWithError(401, "Invalid login");
        try {
            $tokenResponse = $this->generateJwt($user);       
            
            $this->respond($tokenResponse);
        } catch(Exception $e) {
            return $this->respondWithError(401, $e->getMessage());
        }
    }

    public function generateJwt(User $user): array {
        $secret_key = 'thisisasecretkey';
        
        $domain = 'http://localhost';

        $issuedAt = time();
        $payload = array(
            "iss" => $domain, 
            "aud" => $domain, 
            "iat" => $issuedAt,
            "nbf" => $issuedAt, 
            "exp" => ($issuedAt + 1800000), // expiration time is set at +600 seconds (10 minutes)
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

    public function create() {
        try {
            $user = $this->createObjectFromPostedJson("Models\\User");
            $user = $this->service->create($user);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($user);
    }

    public function getById(int $id) {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        $user = $this->service->getUser($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$user) {
            $this->respondWithError(404, "Product not found");
        }

        $this->respond($user);
    }

    public function update(int $id) {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $user = $this->createObjectFromPostedJson("Model\\User");
            $user = $this->service->update($user, $id);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($user);
    }
}