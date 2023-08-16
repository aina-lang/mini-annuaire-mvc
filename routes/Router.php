<?php


namespace app;


require_once "app/Request.php";
require_once "app/Response.php";
require_once "controllers/EntryController.php";
require_once "controllers/CategoryController.php";

use app\Request;
use app\Response;
use controllers\EntryController;
use controllers\CategoryController;

class Router
{
    protected $routes = [];
    public $request;

    public $response;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    public function get($path, $callback)
    {
        $path = preg_replace('/\{(\w+)\}/', '(?<$1>\d+)', $path);

        $this->routes['get'][$path] = $callback;
    }
    public function post($path, $callback)
    {
        $path = preg_replace('/\{(\w+)\}/', '(?<$1>\d+)', $path);
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {


        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = false;
        $routeParams = [];
        // var_dump($this->routes);
        // exit;

        foreach ($this->routes[$method] as $routePath => $routeCallback) {
            $pattern = '#^' . preg_replace('/\{(.+?)\}/', '([^/]+)', $routePath) . '$#';
            if (preg_match($pattern, $path, $matches)) {
                $callback = $routeCallback;
                $routeParams = array_slice($matches, 1);

                break;
            }
        }

       

        if ($callback === false) {
            $this->response->setStatusCode(404);
            return;
        }

        if (is_string($callback)) {
            return $this->renderView($callback, $routeParams);
        }

        if (is_array($callback)) {
            $controller = $callback[0];
            $action = $callback[1];

            if (is_string($controller)) {
                $controller = new $controller();
            }

            $args = array_merge([$this->request], $routeParams);
            $callbackArgs = array_values($args);


            if (method_exists($controller, $action)) {

                return call_user_func_array([$controller, $action], [$callbackArgs]);
            } else {
            }
        }
    }

    public function renderView($view,  $params)
    {
        $layout_content = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        echo str_replace('{{content}}', $viewContent, $layout_content);
    }

    protected function renderOnlyView($view,  $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        $path = $this->request->getPath();

        $segments = explode('/', $path);

        $cleanPath = $segments[1];

        if ($cleanPath === "" || $cleanPath === "/") {
            $viewPath = App::$ROOT_DIR . "/views/categories/$view.php";
        } else {
            $viewPath = App::$ROOT_DIR . "/views/" . $cleanPath . "/$view.php";
        }

        include_once $viewPath;

        return ob_get_clean();
    }

    public function layoutContent()
    {
        ob_start();
        include_once App::$ROOT_DIR . '/views/layouts/header.php';
        include_once App::$ROOT_DIR . "/views/layouts/main.php";
        return ob_get_clean();
    }
}
