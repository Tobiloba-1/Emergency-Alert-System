<?php

class Router
{
    /** Turns the request URL into a route key such as "admin/dashboard". */
    public static function currentPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = rawurldecode($path);

        // Strip the folder the app lives in (e.g. /eass/public).
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        if ($base !== '' && ($path === $base || str_starts_with($path, $base . '/'))) {
            $path = substr($path, strlen($base));
        }

        return strtolower(trim($path, '/'));
    }

    public static function run(array $routes): void
    {
        $path = self::currentPath();

        if (!isset($routes[$path])) {
            self::notFound();
        }

        $route = $routes[$path];

        if (!empty($route['roles'])) {
            if (!Session::isLoggedIn()) {
                header('Location: ' . APP_URL . '/login');
                exit;
            }
            if (!in_array(Session::role(), $route['roles'], true)) {
                self::forbidden();
            }
        }

        $controller = $route['controller'];
        $action     = $route['action'];

        if (!class_exists($controller) || !method_exists($controller, $action)) {
            http_response_code(500);
            echo "500: the route \"/$path\" points to a missing controller or method.";
            exit;
        }

        $result = $controller::$action();

        // Page routes return data for a view. JSON and redirect routes have no view.
        if (isset($route['view'])) {
            View::render($route['view'], is_array($result) ? $result : []);
        }
    }

    private static function notFound(): never
    {
        http_response_code(404);
        View::render('errors/404', ['page_title' => 'Page not found']);
        exit;
    }

    private static function forbidden(): never
    {
        http_response_code(403);
        View::render('errors/403', ['page_title' => 'Access denied']);
        exit;
    }
}