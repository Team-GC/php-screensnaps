# php-screensnaps

A simple php library to interact with the APIs for screenshot generation on screensnaps.io.

## Installation

You can install the package via composer:

```
composer require team-gc/php-screensnaps
```

# Documentation

To get stared, you'll need an `api_key` and `user_id` to make requests, you can sign up for free at https://screensnaps.io.

## Methods

Refer to the documentation on https://screensnaps.io/docs/intro on how to make certain calls.

## Intialize Class

```php

use TeamGC\ScreensnapsIO;

$screensnapsIO = new ScreensnapsIO(["apiKey" => API_KEY_HERE, "userId" => USERID]);

```

### `/screenshots`

Get the last 15 screenshots on your account.

```php

$params =[
    "offset" => 0,
    "limit" => 15
];

try {
    $snaps = $screensnapsIO->screenshots($params);
} catch (Exception $e) {
    echo $e->getMessage();
}

```

### `/screenshot`

Take a screenshot of of URL or HTML depending on your params

```php

$params =[
    "url" => "https://google.com"
];

try {
    $snaps = $screensnapsIO->screenshot($params);
} catch (Exception $e) {
    echo $e->getMessage();
}

```

### `/status`

This is a ping to let you know the status of the service.

```php


try {
    $snaps = $screensnapsIO->status();
} catch (Exception $e) {
    echo $e->getMessage();
}

```

### Testing

Make sure to create a `.env` file in your `tests` folder like this:

```
USER_ID="USER_ID_HERE"
API_KEY="API_KEY_HERE"
```

```bash
composer test tests
```

## Credits

- [Greg Avola](https://github.com/team-gc)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
