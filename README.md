# Guardian

[![Build Status](https://travis-ci.org/timtegeler/guardian.svg?branch=master)](https://travis-ci.org/timtegeler/guardian)
[![Coverage Status](https://coveralls.io/repos/github/timtegeler/guardian/badge.svg?branch=master)](https://coveralls.io/github/timtegeler/guardian?branch=master)
[![GitHub license](https://img.shields.io/github/license/timtegeler/guardian.svg)]()

**Guardian** provides an adapter between an authentication backend and your PSR-15 middleware stack

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

The focus of Guardian is on the adaptation of an authentication backend with a PSR-15 middleware stack. 

This means that Guardian itself is not capable of providing authentication e.g. [Basic access authentication](https://en.wikipedia.org/wiki/Basic_access_authentication). But Guardian ships with a simple interface that can be implemented by the authentication backend. The interface consists of two methods. 
 
```php
<?php

interface AuthenticationInterface
{

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function authenticate(ServerRequestInterface $request);
    
    /**
    * @return ResponseInterface
    */
    public function getAuthenticationFailedResponse();

}
```

### authenticate

The `authenticate` method receives the current request as a parameter and must return a `boolean` (which stands for `access approved` respectively `access denied`) . 

### getAuthenticationFailedResponse

The `getAuthenticationFailedResponse` method must return a `ResponseInterface` instance. It's called by Guardian in case of `access denied` to return a `ResponseInterface` instance to the middleware pipeline. The fact that the authentication backend is in charge to provide a proper `ResponseInterface` instance is due to the need of custom properties.

E.g. an authentication backend, which supports the [Basic access authentication](https://en.wikipedia.org/wiki/Basic_access_authentication), "should return a response whose header contains a *HTTP 401 Unauthorized status* and a *WWW-Authenticate field.* The WWW-Authenticate field for basic authentication (used most often) is constructed as following:  `WWW-Authenticate: Basic realm="User Visible Realm"`" [[WIKI]](https://en.wikipedia.org/wiki/Basic_access_authentication#Server_side)

