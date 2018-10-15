<?php

namespace Api\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public function __construct($code = 400, $message = '')
    {
        parent::__construct($code, $message);
    }

    public function toArray(): array
    {
        $data = [
            'code' => $this->getStatusCode(),
            'message' => $this->getMessage()
        ];

        return $data;
    }
}