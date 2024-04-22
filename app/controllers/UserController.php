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
        if (is_null($user)) return $this->respondWithError(404, "User not found.");
        if(!$user) return $this->respondWithError(401, "Invalid login.");
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
                "user_role" => $user->user_role
        ));

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "username" => $user->name,
                "user_role" => $user->user_role,
                "expireAt" => ($issuedAt + 1800000)
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
        if ($this->checkJWTToken()) {
            $user = $this->service->getById($id);
    
            if (!$user) {
                $this->respondWithError(404, "User not found");
            }
    
            $this->respond($user);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }

    public function update(int $id) {
        if ($this->checkJWTToken()) {
            try {
                $user = $this->createObjectFromPostedJson("Model\\User");
                $user = $this->service->update($user, $id);
    
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
            $this->respond($user);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }

    public function getAll() {
        if ($this->checkJWTToken()) {
            try {   
                $this->respond($this->service->getAll());
            } catch (Exception $e) { 
                $this->respondWithError(500, $e->getMessage());
            }
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }
}