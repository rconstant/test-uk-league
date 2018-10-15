<?php

namespace Api\Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidatorException extends ApiException
{
    private $constraintViolationList;

    public function __construct(ConstraintViolationListInterface $constraintViolationList, $message = 'Validation failed', $code = 400)
    {
        parent::__construct($code, $message);
        $this->constraintViolationList = $constraintViolationList;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $errors = [];
        foreach ($this->constraintViolationList as $constraintViolation) {
            $errors[] = $this->constraintViolationToArray($constraintViolation);
        }
        $data['errors'] = $errors;
        return $data;
    }


    protected function constraintViolationToArray(ConstraintViolationInterface $constraintViolation)
    {
        $result = [
            'property' => (string) $constraintViolation->getPropertyPath(),
            'message' => $constraintViolation->getMessage()
        ];

        if(!empty($constraintViolation->getParameters()['data'])) {
            $result['data'] = $constraintViolation->getParameters()['data'];
        }

        return $result;
    }
}