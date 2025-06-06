<?php

declare(strict_types=1);

namespace NoCake\MsgGo\Client\Exception;

use RuntimeException;
use Throwable;

/**
 * Represents an error returned by the MsgGO API.
 */
class ApiException extends RuntimeException
{
    private string $error;

    /**
     * ApiException constructor.
     *
     * @param string $message The exception message.
     * @param int $code The HTTP status code or a custom error code.
     * @param string $error
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = '', int $code = 0, string $error = '', ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    /**
     * Gets error name.
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}
