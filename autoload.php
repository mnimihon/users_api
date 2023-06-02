<?php

spl_autoload_register(function ($classname) {
    require_once dirname(__FILE__) . "/" . str_replace("\\", "/", $classname) . '.php';
});