<?php

use Slim\Http\Request;
use Slim\Http\Response;

include './Models/AccountModel.php';
include './Controllers/AccountControllers.php';

$app->post('/apinunua/create_account', function (Request $request, Response $response, $args) {

    $cls = new AccountControllers();
    $infos = $cls->CreateCompteOnly($request, $response);
    return $response->write(json_encode($infos))
        ->withStatus($infos["code"])
        ->withHeader("Content-Type", "application/json");
});

$app->post('/apinunua/update_account', function (Request $request, Response $response, $args) {

    $cls = new AccountControllers();
    $infos = $cls->UpdateCompteOnly($request, $response);
    return $response->write(json_encode($infos))
        ->withStatus($infos["code"])
        ->withHeader("Content-Type", "application/json");
});

$app->post('/apinunua/login', function (Request $request, Response $response, $args) {

    $cls = new AccountControllers();
    $infos = $cls->LoginCompteOnly($request, $response);
    return $response->write(json_encode($infos))
        ->withStatus($infos["code"])
        ->withHeader("Content-Type", "application/json");
});

$app->post('/apinunua/createEntreprise', function (Request $request, Response $response, $args) {

    $cls = new AccountControllers();
    $infos = $cls->CreateCompteEntreprise($request, $response);
    return $response->write(json_encode($infos))
        ->withStatus($infos["code"])
        ->withHeader("Content-Type", "application/json");
});

$app->post('/apinunua/permission', function (Request $request, Response $response, $args) {

    $cls = new AccountControllers();
    $infos = $cls->CreatePermission($request, $response);
    return $response->write(json_encode($infos))
        ->withStatus($infos["code"])
        ->withHeader("Content-Type", "application/json");
});