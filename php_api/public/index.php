<?php

require "../src/Config/initialize.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uri = explode("/", $uri);

$database = new Database();
$conn = $database->connect();   
$requestMethod = $_SERVER["REQUEST_METHOD"];

if (!in_array($uri[2], ["movie", "director", "genre"], true)) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

if ($uri[2] === "movie") {  
    $movieId = null;
    if (isset($uri[3])) {
        $movieId = intval($uri[3]);
    }

    $controller = new MovieController($conn, $requestMethod, $movieId);
    $controller->processRequest();
}

if ($uri[2] === "director") {
    $directorId = null;
    if (isset($uri[3])) {
        $directorId = intval($uri[3]);
    }

    $controller = new DirectorController($conn, $requestMethod, $directorId);
    $controller->processRequest();
}

if ($uri[2] === "genre") {
    $genreId = null;
    if (isset($uri[3])) {
        $genreId = intval($uri[3]);
    }

    $controller = new GenreController($conn, $requestMethod, $genreId);
    $controller->processRequest();
}
