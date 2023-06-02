<?php

require __DIR__ . '/autoload.php';
(new \app\Core\Router(new \app\Core\Request($_SERVER)))->run();