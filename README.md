# MsgGO PHP SDK

[![Latest Stable Version](https://poser.pugx.org/msggo/sdk-php/v/stable)](https://packagist.org/packages/msggo/sdk-php)
[![Total Downloads](https://poser.pugx.org/msggo/sdk-php/downloads)](https://packagist.org/packages/msggo/sdk-php)
[![License](https://poser.pugx.org/msggo/sdk-php/license)](https://packagist.org/packages/msggo/sdk-php)

This is the official PHP SDK for interacting with the [MsgGO](https://msggo.io/) API. It allows you to easily send events to your MsgGO inbox from your PHP applications.

The SDK is compliant with PSR standards.

## Requirements

- PHP 7.4 or higher
- cURL extension
- JSON extension

## Installation

You can install the SDK via [Composer](https://getcomposer.org/). Run the following command in your project directory:

```bash
composer require msggo/sdk-php
```

## Usage

First, you need to obtain an API key from your MsgGO dashboard.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use MsgGo\Client\Client;
use MsgGo\Client\Exception\ApiException; // Will be created in a subsequent step

// Replace 'YOUR_MSGGO_API_KEY' with your actual API key
$apiKey = 'YOUR_MSGGO_API_KEY';

try {
    // Basic instantiation
    $client = new Client($apiKey);

    // Optionally, override the base API URL
    // $clientWithCustomUrl = new Client($apiKey, ['api_base_url' => 'https://custom.msggo.instance.com']);

    $eventName = 'new-user-registration';
    $eventData = [
        'username' => 'john_doe',
        'email' => 'john.doe@example.com',
        'source' => 'website_signup_form'
    ];

    // Send an event
    $response = $client->sendEvent($eventName, $eventData);

    if ($response['ok']) {
        echo "Event '{$eventName}' sent successfully!\n";
        // Optional: print response data
        // print_r($response['data'] ?? []);
    } else {
        echo "Failed to send event '{$eventName}'.\n";
        echo "Status Code: " . ($response['statusCode'] ?? 'N/A') . "\n";
        echo "Message: " . ($response['message'] ?? 'No message provided.') . "\n";
    }

} catch (\InvalidArgumentException $e) {
    // Handle errors related to invalid arguments (e.g., empty API key or event name)
    echo "Argument Error: " . $e->getMessage() . "\n";
} catch (\RuntimeException $e) {
    // Handle runtime errors (e.g., cURL issues, JSON decoding errors)
    echo "Runtime Error: " . $e->getMessage() . "\n";
}
// Ensure cURL handle is closed if client was instantiated
// The client's __destruct method handles this, but explicit closure can be added if needed.
// if (isset($client)) {
//    $client->closeCurlHandle();
// }

?>
```

## API Documentation

For more details on the MsgGO API and its capabilities, please refer to the [official MsgGO documentation](https://msggo.io/documentation) (as referenced in `ai-docs/ncj.txt`). The primary endpoint used by this SDK is `https://msggo.io/inbox`.

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue.

## License

The MsgGO PHP SDK is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.