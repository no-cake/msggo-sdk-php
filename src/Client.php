<?php

declare(strict_types=1);

namespace MsgGo;

use MsgGo\Client\Exception\ApiException;

/**
 * MsgGO API Client.
 */
class Client
{
    private const DEFAULT_API_BASE_URL = 'https://msggo.io';
    private const ENDPOINT_INBOX = '/inbox';

    private string $apiKey;
    private string $apiBaseUrl;
    private ?\CurlHandle $curlHandle = null;

    /**
     * Client constructor.
     *
     * @param string $apiKey Your MsgGO API key.
     * @param array{api_base_url?: string} $options Additional options for the client.
     *        - `api_base_url` (string): Override the base URL for API requests. Defaults to 'https://msggo.io'.
     */
    public function __construct(string $apiKey, array $options = [])
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('API key cannot be empty.');
        }
        $this->apiKey = $apiKey;
        $this->apiBaseUrl = $options['api_base_url'] ?? self::DEFAULT_API_BASE_URL;

        if (empty($this->apiBaseUrl)) {
            throw new \InvalidArgumentException('API base URL cannot be empty.');
        }
    }

    /**
     * Sends an event to MsgGO.
     *
     * @param array<string, mixed> $data Event data.
     * @return array{ok: bool, statusCode: int, message?: string, data?: array<mixed>} The API response if successful.
     * @throws \RuntimeException If the cURL request fails or the response cannot be decoded.
     * @throws ApiException If the API returns an error (e.g., authentication failure, validation error).
     */
    public function event(array $data = []): bool
    {
        $url = rtrim($this->apiBaseUrl, '/') . self::ENDPOINT_INBOX;

        $ch = $this->getCurlHandle();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json', 
            'X-MsgGO-Key: ' . $this->apiKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseBody = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($responseBody === false) {
            $error = curl_error($ch);
            $this->closeCurlHandle(); // Close handle on error
            throw new \RuntimeException('cURL request failed: ' . $error);
        }

        $decodedResponse = json_decode($responseBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->closeCurlHandle(); // Close handle on error
            // Attempt to provide more context for non-JSON responses if it's an error
            if ($httpStatusCode >= 400) {
                 throw new \RuntimeException(
                    sprintf(
                        'Failed to decode JSON response. Status: %d. Response: %s',
                        $httpStatusCode,
                        substr($responseBody, 0, 200) // Show first 200 chars
                    )
                );
            }
            // If not an error status, but still not JSON, it's unexpected.
            throw new \RuntimeException('Failed to decode JSON response: ' . json_last_error_msg());
        }
        
        if (!isset($decodedResponse['ok'])) {
            throw new ApiException('Malformed respnose.', 0, 'malformed_response');
        }

        // Throw ApiException if the API call was not successful
        if ($decodedResponse['ok'] === false) {
            throw new ApiException(
                $decodedResponse['errors'][0]['message'],
                $httpStatusCode,
                $decodedResponse['errors'][0]['error']
            );
        }

        return true;
    }

    /**
     * Initializes and returns a cURL handle.
     * Reuses the handle if already initialized.
     */
    private function getCurlHandle(): \CurlHandle
    {
        if ($this->curlHandle === null) {
            $this->curlHandle = curl_init();
            if ($this->curlHandle === false) {
                throw new \RuntimeException('Failed to initialize cURL handle.');
            }
        }
        // Reset options that might persist from previous requests if reusing handle
        // For this simple client, direct initialization per call might be cleaner,
        // but let's keep it for potential future reuse patterns.
        // curl_reset($this->curlHandle); // Requires PHP 5.5+
        return $this->curlHandle;
    }

    /**
     * Closes the cURL handle if it's open.
     */
    public function closeCurlHandle(): void
    {
        if ($this->curlHandle !== null) {
            curl_close($this->curlHandle);
            $this->curlHandle = null;
        }
    }

    /**
     * Destructor to ensure cURL handle is closed.
     */
    public function __destruct()
    {
        $this->closeCurlHandle();
    }
}