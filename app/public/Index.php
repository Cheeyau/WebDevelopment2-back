<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . '/../vendor/autoload.php';

$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers'); 

// $router->setBasePath("/WebDevelopment2");

$router->post("/users/login", "UserController@login");

$router->get("/orders", "OrderController@getAll");
$router->get("/orders/(\d+)", "OrderController@getById");
$router->post("/orders", "OrderController@create");
$router->put("/orders/(\d+)", "OrderController@update");

$router->get("/transactions", "TransactionController@getAll");
$router->get("/transactions/(\d+)", "TransactionController@getById");
$router->post("/transactions", "TransactionController@create");
$router->put("/transactions/(\d+)", "TransactionController@update");

$router->get("/products", "ProductController@getAll");
$router->get("/products/(\d+)", "ProductController@getById");
$router->post("/products", "ProductController@create");
$router->put("/products/(\d+)", "ProductController@update");
$router->delete("/products/(\d+)", "ProductController@delete");

$router->run();