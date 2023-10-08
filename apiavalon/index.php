<?php
/**
 * Step 1: Require the Slim Framework using Composer's autoloader
 *
 * If you are not using Composer, you need to load Slim Framework with your own
 * PSR-4 autoloader.
 */
//require 'vendor/autoload.php';
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}
require __DIR__ . '/vendor/autoload.php';
session_start();
// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);
$app->add(new \CorsSlim\CorsSlim());
// Set up dependencies
require __DIR__ . '/src/dependencies.php';
// Register middleware
require __DIR__ . '/src/middleware.php';
// Register routes
require __DIR__ . '/src/routes.php';

// $app->add(new Tuupola\Middleware\JwtAuthentication([
//     "secret" => "b54606acb8682088b34c295030ce8101508c0def316ac6fa59ab757d653f903173ce47798e9ae2407343013373309584",
//     "secure" => false,
//     "error" => function ($response, $arguments) {
//         $data["status"] = "error";
//         $data["message"] = $arguments["message"];
//         return $response
//             ->withHeader("Content-Type", "application/json")
//             ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
//     }
// ]));

// Run app
$app->run();