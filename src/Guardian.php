<?php

namespace TimTegeler\Guardian;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Guardian
 * @package TimTegeler\Guardian
 */
class Guardian implements MiddlewareInterface
{
    /**
     * @var AbstractAuthentication
     */
    private $authentication;

    /**
     * Guardian constructor.
     * @param AbstractAuthentication $authentication
     */
    public function __construct(AbstractAuthentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if ($this->authentication->authenticate($request)) {
            return $delegate->process($request);
        } else {
            return $this->authentication->getAuthenticationFailedResponse();
        }
    }
}
