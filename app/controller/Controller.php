<?php

namespace Controllers;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Exception;

class Controller {

    public function respond($data):mixed {
        $this->respondWithCode(200, $data);
    }

    public function respondWithError($httpcode, $message): mixed {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data): mixed {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    public function createObjectFromPostedJson($className): mixed {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $object = new $className();
        foreach ($data as $key => $value) {
            if (is_object($value)) continue;
            $object->{$key} = $value;

            // $setter = "set" . ucfirst($key);
            // $object->$setter($value);
        }
        return $object;
    }

    public function checkJWTToken(): mixed {
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
        $secret_key = SECRET_KEY;
 
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                // username is now found in
                // echo $decoded->data->username;
                return $decoded;
            } catch (Exception $e) {
                $this->respondWithError(401, $e->getMessage());
            }
        }
   }
   
   public function paginator(): array {
        $items = [];
        /// shorthanded if else statement
        $items[0] = (isset($_GET["offset"]) && is_numeric($_GET["offset"])) ? $_GET["offset"] : null;
        $items[1] = (isset($_GET["limit"]) && is_numeric($_GET["limit"])) ? $_GET["limit"] : null;
        
        return $items;
   }
}