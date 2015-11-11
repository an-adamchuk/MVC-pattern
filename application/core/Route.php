<?php

namespace App\Core;

/**
 * Class Route to manage routes
 * @package App\Core
 */
class Route
{
    private $controller = '';
    private $action = '';
    private $tokens = [];

    private $url;
    private $routes;
    private $method;

    /**
     * Route constructor.
     *
     * @param $url
     * @param $routes
     * @param $method
     */
    public function __construct($method, $url, $routes)
    {
        $this->url = $url;
        $this->routes = $routes;
        $this->method = $method;

        $this->parse($routes[$method]);

    }

    /**
     * Show Route object.
     *
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'controller' => $this->controller,
            'action' => $this->action,
            'tokens' => $this->tokens
        ];
    }

    /**
     * Get controller.
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get tokens list.
     *
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Parse route. Match url with uri in routes list
     * and set for matched route: controller, action, tokens
     * @param $routes
     */
    private function parse($routes)
    {

        foreach ($routes as $route) {

            preg_match("@^" . $route['uri'] . "$@", $this->url, $matches);

            if ($matches) {
                $routeAction = explode('@', $route['action']);

                if (!empty($routeAction[0])) {
                    $this->controller = ucfirst($routeAction[0]);
                }

                if (!empty($routeAction[1])) {
                    $this->action = $routeAction[1];
                }

                $tokens = [];
                foreach ($matches as $key=>$match) {

                    // Not interested in the complete match, just the tokens
                    if ($key == 0) {
                        continue;
                    }

                    // Save the token
                    $tokens[$route['tokens'][$key-1]] = $match;

                }

                $this->tokens = $tokens;

            }
        }
    }
}