<?php

namespace Controllers;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Exception;
use \Models\Paginator;

class Controller {

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
        foreach ($data as $key => $value) {
            if (is_object($value)) continue;
            $object->{$key} = $value;
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
        $jwt = $arr[1];

        // Decode JWT
        $secret_key = 'thisisasecretkey';
 
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                return $decoded;
            } catch (Exception $e) {
                $this->respondWithError(401, $e->getMessage());
            }
        }
   }
   
    public function paginator(): Paginator {
        $pages = $this->createObjectFromPostedJson('Models\\Paginator');
        $pages->offset = isset($pages->offset) ? $pages->offset : 0;
        $pages->limit = isset($pages->limit) ? $pages->limit : 5;   
        return $pages;
   }
}