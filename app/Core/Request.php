<?php

namespace app\Core;

class Request
{
    /**
     * @var array
     */
    private $server;

    public function __construct(array $server)
    {
        $this->server = $server;
    }

    public function getUri()
    {
        $uriArray = explode('?', $this->server['REQUEST_URI']);
        return $uriArray[0];
    }

    public function getRequestMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getParameters(): array
    {
        $parametersArray = [];
        $parameters = explode('?', $this->server['REQUEST_URI']);
        parse_str($parameters[1], $parametersArray);
        return $parametersArray;
    }

    public function getData(string $input)
    {
        $inputData = json_decode($input, true);
        $inputData = $inputData ?? [];
        return array_merge($inputData, $this->getParameters());
    }
}