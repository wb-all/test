<?php

declare(strict_types=1);

namespace App\Comment\Application\Exception;

class ValidationException extends \RuntimeException
{
    private string $propertyPath;
    private string $errorMessage;

    public function __construct(string $propertyPath, string $errorMessage)
    {
        parent::__construct($propertyPath . ' : ' . $errorMessage);

        $this->propertyPath = $propertyPath;
        $this->errorMessage = $errorMessage;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
