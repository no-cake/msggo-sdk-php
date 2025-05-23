# MsgGO PHP SDK

This is the official PHP SDK for interacting with the [MsgGO](https://msggo.io/) API. It allows you to easily send events to your MsgGO inbox from your PHP applications.

The SDK is compliant with PSR standards.

## Requirements

- PHP 7.4 or higher
- cURL extension
- JSON extension

## Installation

You can install the SDK via [Composer](https://getcomposer.org/). Run the following command in your project directory:

```bash
composer require nocake/msggo
```

## Usage

First, you need to obtain an API key from your MsgGO dashboard.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use MsgGo\Client;
use MsgGo\Client\Exception\ApiException;

// Replace 'YOUR_MSGGO_API_KEY' with your actual API key
$apiKey = 'YOUR_MSGGO_API_KEY';

try {
    // Basic instantiation
    $client = new Client($apiKey);

    // Optionally, override the base API URL
    // $clientWithCustomUrl = new Client($apiKey, ['api_base_url' => 'https://custom.msggo.instance.com']);

    $eventData = [
        'event' => 'my-event',
        'username' => 'john_doe',
        'email' => 'john.doe@example.com',
        'source' => 'website_signup_form'
    ];

    // Send an event
    $client->event($eventName, $eventData);

} catch (\InvalidArgumentException $e) {
    // Handle errors related to invalid arguments (e.g., empty API key)
    echo "Argument Error: " . $e->getMessage() . "\n";
} catch (ApiException $e) {
    // Handle API-specific errors (e.g., authentication failure, validation error)
    echo "API Error: " . $e->getMessage() . "\n";
    echo "Error: " . $e->getError() . "\n";
} catch (\RuntimeException $e) {
    // Handle other runtime errors (e.g., cURL issues, JSON decoding errors not from API)
    echo "Runtime Error: " . $e->getMessage() . "\n";
}

?>
```

## API Documentation

For more details on the MsgGO API and its capabilities, please refer to the [official MsgGO documentation](https://msggo.io/documentation).

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue.

## License

The MsgGO PHP SDK is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.