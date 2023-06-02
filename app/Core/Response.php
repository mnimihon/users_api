<?php

namespace app\Core;

class Response
{
    private $json;
    private $statusCode;

    public function setStatusCode(int $code): Response
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setJson(array $data): Response
    {
        $this->json = json_encode($data, true);
        return $this;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        echo $this->json;
    }

}