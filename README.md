# Guardian

[![GitHub license](https://img.shields.io/github/license/timtegeler/guardian.svg)]()
[![Build Status](https://travis-ci.org/timtegeler/guardian.svg?branch=master)](https://travis-ci.org/timtegeler/guardian)

**Guardian** provides a adapter between an authentication backend and your PSR-15 middleware stack

- Compatible to the PSR-15 middleware interface 
- Adaptable to your authentication backend with a simple interface

```php
<?php

// create a new authentication backend which implements the AuthenticationInterface
$authenticationBackend = new AuthenticationBackend();

// using e.g. mindplay-dk/middleman as a dispatcher for the middleware stack
$response = (new Dispatcher(
    [   
        // inject Guardian with the authentication backend instance
        new Guardian($authenticationBackend),
        // ... more middlwares e.g. a router
        new Router()
    ]
))->dispatch($request);
```

## Authentication Backend

The focus of Guardian is the adaptation of an authentication backend with a PSR-15 middleware stack. 

This means that Guardian itself is not capable of providing authentication e.g. [Basic access authentication](https://en.wikipedia.org/wiki/Basic_access_authentication). But Guardian ships with a simple interface that can be implement by the authentication backend. The `authenticate` method receives the current request as a parameter and must return a `boolean` (which stands for `access approved` respectively `access denied`) . 
 
```php
<?php

interface AuthenticationInterface
{

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function authenticate(ServerRequestInterface $request);

}
```