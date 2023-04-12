# RunCloud Agency API SDK

## A PHP SDK for RunCloud Agency API v3

### 1. Installation

``composer require runcloudio/php-api-sdk``

### 2. How to use it ?

``$client = new \RunCloudIO\PHPApiSDK\Client();``
``$client->setToken(<Your agency API token</strong>)->send('agency-api' OR 'core-api', <action name>', <URL parameters>, <form data>);``

### Note

1. For action name, please refer valid names in `/docs/index.php` .
2. URL parameters must be in array form e.g ``[1, 2, 3]``, based by required parameters in route with `%s` accordingly, for route urls please refer to  `/docs/index.php`.
3. Form data must be in associative array with key => value such as
``['id' => 1, 'name' => 'Test Name']``
