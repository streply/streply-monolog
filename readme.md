# Streply Monolog Handler

## Install

Use composer to install Streply Monolog Handler.

```bash
composer require streply/streply-monolog
```

## Setup

Set up Monolog logger with Streply.

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use Monolog\Logger;
use Streply\Monolog\StreplyMonologHandler;

$logger = new Logger("example-app");
$logger->pushHandler(
    new StreplyMonologHandler(
        "https://clientPublicKey@api.streply.com/projectId"
    )
);
```

You can find the DSN code of the project in the [projects](https://app.streply.com/projects0) tab in your Streply account.

As a second parameter, you can set an array with optional parameters.

```php
<?php

$logger->pushHandler(
    new StreplyMonologHandler(
        "https://clientPublicKey@api.streply.com/projectId",
        [
            'environment' => 'production',
            'release' => 'my-project-name@2.3.12',
        ]
    )
);
```

For more configuration options, see the [Configuration](https://docs.streply.com/configuration) tab.

## Start logging

Use Monolog as always :)

```php
<?php

$logger->error("Some error here");
$logger->info("Some user logged in", [
    'userName' => 'Joey'
]);
$logger->debug($sqlQuery);
```
## Need help?

Please let us know at [support@streply.com](mailto:support@streply.com)
