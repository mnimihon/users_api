<?php

namespace app\Core;

use app\Controllers\UsersController;

class Router
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        (new UsersController($this->request))->run();
    }
}