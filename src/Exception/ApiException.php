<?php

declare(strict_types=1);

namespace MsgGo\Client\Exception;

/**
 * Represents an error returned by the MsgGO API.
 */
class ApiException extends \RuntimeException
{
    /**
     * @var array<string, mixed>
     */
    private array $response;

    /**
     * ApiException constructor.
     *
     * @param string $message The exception message.
     * @param int $code The HTTP status code or a custom error code.
     * @param array<string, mixed> $response The full decoded API response.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = "", int $code = 0, array $response = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    /**
     * Gets the full API response that caused the exception.
     *
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * Gets a specific field from the API error response, if available.
     *
     * @param string $field The field name to retrieve (e.g., 'message' from the API error structure).
     * @return mixed|null The value of the field or null if not found.
     */
    public function getResponseField(string $field)
    {
        return $this->response[$field] ?? null;
    }
}