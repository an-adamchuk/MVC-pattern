<?php

namespace App\Core;

use App\Core\Model;

/**
 * Class Controller
 * @package App\Core
 */
class Controller {

    public $model;

    public $request;

    /**
     * Constructor.
     *
     * @param \App\Core\Model $model
     * @param $request
     */
    function __construct(Model $model, $request)
    {
        $this->model = $model;
        $this->request = $request;
    }
}