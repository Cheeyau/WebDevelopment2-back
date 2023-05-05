<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$router = new \Bramus\Router\Router();

$router->setNamespace('app\Controller');

$router->setBasePath('/webdevelopment2');

$user = '/user';
$router->post("/user/login", "UserController@login");

$order = "/order";
$orderController = "OrderController";
$router->get("$order", "$orderController@getAll");
$router->get("$order/(\d+)", "$orderController@getById");
$router->post("$order", "$orderController@create");
$router->put("$order/(\d+)", "$orderController@update");

$transaction = "/transaction";
$transactionController = "TransactionController";
$router->get("$transaction", "$transactionController@getAll");
$router->get("$transaction/(\d+)", "$transactionController@getById");
$router->post("$transaction", "$transactionController@create");
$router->put("$transaction/(\d+)", "$transactionController@update");

$product = "/product";
$productController = "ProductController";
$router->get("$product", "$productController@getAll");
$router->get("$product/(\d+)", "$productController@getById");
$router->post("$product", "$productController@create");
$router->put("$product/(\d+)", "$productController@update");
$router->delete("$product/(\d+)", "$productController@delete");

$router->run();