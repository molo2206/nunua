<?php


use Slim\Http\Request;
use Slim\Http\Response;
use Tuupola\Middleware\JwtAuthentication;

require 'vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($config);

// Access configuration
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

// Config
include './Config/declare.php';
include './Config/Vars.php';
include './Config/dbo.php';

// Libs
include './lib/courier/index.php';

// $app->add(new JwtAuthentication([
//     'path' => '/',
//     'ignore' => [ 
//         "/api/doc",
//         "/lib",
//         "/"
//     ],
//     'attribute' => 'decoded_token_data',
//     'secret' => JWT_TOKEN_KEY,
//     'algorithm' => ['HS512'],
//     'error' => function ($response, $arguments) {
//         $data['code'] = 400;
//         $data['message'] = $arguments['message'];

//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
//     }
// ]));

// Routes

include './Routes/root_account.php';
$app->get('/', function (Request $request, Response $response, $args) {

    $infos = [];
    return $response->write(json_encode($infos))
        ->withStatus($infos["code"])
        ->withHeader("Content-Type", "application/json");
});

$app->run();
