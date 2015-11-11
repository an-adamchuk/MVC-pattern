<?php

namespace App\Core;

/**
 * Class Request
 *
 * @package App\Core
 */
abstract class Request
{
    /**
     * Return request params.
     *
     * @return array
     */
    public static function getRequestParams() {
        $method = $_SERVER['REQUEST_METHOD'];
        $params = [];
        if ($method == "PUT") {
            parse_str(file_get_contents('php://input'), $params);
            $GLOBALS["_{$method}"] = $params;
            // Add these request vars into _REQUEST, mimicing default behavior, PUT/DELETE will override existing COOKIE/GET vars
            $_REQUEST = $params + $_REQUEST;
        } else if ($method == "GET") {
            $params = $_GET;
        } else if ($method == "POST") {
            $params = $_POST;
        }

        array_walk($params, function (&$item, $key)
        {
            $item = htmlspecialchars($item);
        });

        return $params;
    }
}