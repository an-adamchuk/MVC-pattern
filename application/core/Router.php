<?php

namespace App\Core;

/**
 * Class Router
 *
 * @package App\Core
 */
class Router
{
    private $routes;
    private $route;
    /**
     * All of the verbs supported by the router.
     *
     * @var array
     */
    public static $verbs = ['GET', 'POST', 'PUT', 'DELETE'];
    private $url;


    /**
     * Start listen to routes
     */
    public function start()
    {
        $this->url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $this->route = new Route($method, $this->url, $this->routes);

        $entityName = $this->route->getController();


        $modelName = 'App\Models\\'.$entityName.'Model';
        $modelPath = 'application/models/'.$entityName.'Model.php';

        if(file_exists($modelPath)) {
            include $modelPath;
        }

        $controllerName = 'App\Controllers\\' . $entityName . 'Controller';
        $controllerPath = 'application/controllers/'.$entityName . 'Controller.php';

        // include controller
        if(file_exists($controllerPath)) {
            include $controllerPath;
        }
        else {
            Router::ErrorResponse('Page not found.', 404);
        }

        $requestData = Request::getRequestParams();

        // create controoler
        $controller = new $controllerName(new $modelName(), $requestData);
        $action = $this->route->getAction() . 'Action';

        if(method_exists($controller, $action))
        {
            // call controller action
            call_user_func_array(array($controller, $action), $this->route->getTokens());
        }
        else
        {
            Router::ErrorResponse('Page not found.', 404);
        }

    }

    /**
     * Register a new POST route with the router.
     *
     * @param $uri
     * @param $action
     * @param array $tokens
     */
    public function post($uri, $action, $tokens = [])
    {
        $this->addRoute('POST', $uri, $action, $tokens);
    }

    /**
     * Register a new GET route with the router.
     *
     * @param $uri
     * @param $action
     * @param array $tokens
     */
    public function get($uri, $action, $tokens = [])
    {
        $this->addRoute('GET', $uri, $action, $tokens);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param $uri
     * @param $action
     * @param array $tokens
     */
    public function put($uri, $action, $tokens = [])
    {
        $this->addRoute('PUT', $uri, $action, $tokens);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param $uri
     * @param $action
     * @param array $tokens
     */
    public function delete($uri, $action, $tokens = [])
    {
        $this->addRoute('DELETE', $uri, $action, $tokens);
    }

    /**
     * Add a route to the route collection.
     *
     * @param $method
     * @param $uri
     * @param $action
     * @param array $tokens
     */
    public function addRoute($method, $uri, $action, $tokens = []) {
        $this->routes[$method][] = array(
            'uri' => $uri,
            'action' => $action,
            'tokens' => $tokens
        );
    }

    /**
     * Show error.
     */
    public static function ErrorResponse($message = 'Error', $code = 500)
    {
        $response = new JsonResponse(['error' => $message], $code);
        $response->send();
    }
}