<?php

namespace TimTegeler\Guardian;

use GuzzleHttp\Psr7\Response;
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
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * Guardian constructor.
     * @param AuthenticationInterface $authentication
     */
    public function __construct(AuthenticationInterface $authentication)
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
            return (new Response())->withStatus(403);
        }
    }
}
