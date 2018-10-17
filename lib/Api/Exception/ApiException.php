<?php

namespace Api\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ApiException
 * @package Api\Exception
 */
class ApiException extends HttpException
{
    /**
     * ApiException constructor.
     *
     * @param int    $code
     * @param string $message
     */
    public function __construct($code = 400, $message = '')
    {
        parent::__construct($code, $message);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'code' => $this->getStatusCode(),
            'message' => $this->getMessage()
        ];

        return $data;
    }
}