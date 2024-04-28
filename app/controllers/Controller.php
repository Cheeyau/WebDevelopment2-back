<?php

namespace Controllers;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Exception;
use \Models\Paginator;
use \Models\JWTToken;
use \Services\UserService;

class Controller {
    
    private $user_service;

    public function setUserService(UserService $user_service) {
        $this->user_service = $user_service;
    }

    public string $jwt_not_found = 'JWT token was not provided or readable, please login again.';
    public string $user_unauthorized = 'User is unauthorized.';

    function respond($data) {
        $this->respondWithCode(200, $data);
    }

    public function respondWithError($httpcode, $message) {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    public function createObjectFromPostedJson($className) {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        
        $object = new $className();
        if (!is_null($data)) {
            foreach ($data as $key => $value) {
                if (is_object($value)) continue;
                $object->{$key} = $value;
            }
        }
        return $object;
    }

    public function checkJWTToken() {
        // Check for token header
        if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
           $this->respondWithError(401, "No token provided");
        }

        // Read JWT from header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        // Strip the part "Bearer " from the header
        $arr = explode(" ", $authHeader);
        
        if (!isset($arr[1])) {  
            return null;
        } 
        $jwt = $arr[1];

        // Decode JWT
        $secret_key = 'thisisasecretkey';

        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                return new JWTToken($decoded);
            } catch (Exception $e) {
                $this->respondWithError(401, $e->getMessage());
            }
        }
   }
   
    public function paginator(): Paginator {
        $pages = new Paginator();
        if (isset($_GET['user'])) $pages->offset = $_GET['offset'];
        if (isset($_GET['category'])) $pages->category = $_GET['category'];
        $pages->offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        $pages->limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
        return $pages;
    }
    public function checkLoginUser(int $user_id, int $login_user_id) {
        $db_user = $this->user_service->getById($user_id);
        return (!$db_user || (($db_user->id === $login_user_id) && ($db_user->user_role === 0)) || $db_user->user_role > 0) ? false : true;
    }

    public function getUserRoll(int $user_id) {
        return $this->user_service->getById($user_id)->user_role;
    }
}