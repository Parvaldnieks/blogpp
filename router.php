<?php
// Get the request method (GET, POST, PATCH, DELETE, etc.)
$method = $_SERVER["REQUEST_METHOD"];

// Override method if _method is set (for PATCH & DELETE)
if ($method === "POST" && isset($_POST["_method"])) {
    $method = strtoupper($_POST["_method"]);
}

// Parse the requested URL
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Load routes configuration
$routes = require "routes.php";

// Check if exact route exists (e.g., "/post/create")
if (isset($routes[$method][$uri])) {
    list($controller, $action) = explode("@", $routes[$method][$uri]);
} else {
    // Check if it's a dynamic route with an ID (e.g., "/post/1")
    $routeFound = false;

    foreach ($routes[$method] as $routePattern => $handler) {
        $pattern = str_replace("{id}", "(\d+)", $routePattern); // Convert "/post/{id}" to "/post/(\d+)"
        if (preg_match("#^$pattern$#", $uri, $matches)) {
            list($controller, $action) = explode("@", $handler);
            $id = $matches[1]; // Extract the ID
            $routeFound = true;
            break;
        }
    }

    if (!$routeFound) {
        http_response_code(404);
        echo "Lapa nav atrasta!";
        exit();
    }
}

// Include the controller
require_once "controllers/{$controller}.php";

// Instantiate the controller
$instance = new $controller();

// Call the method with or without an ID
if (isset($id)) {
    $instance->$action($id);
} else {
    $instance->$action();
}