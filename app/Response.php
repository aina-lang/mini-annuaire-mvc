<?php

namespace app;

class Response
{

    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }
}
