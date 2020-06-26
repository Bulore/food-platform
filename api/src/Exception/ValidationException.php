<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends \Exception
{
    /** @var ConstraintViolationListInterface */
    private $errors;

    public function __construct(ConstraintViolationListInterface $errors, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return string[]
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
