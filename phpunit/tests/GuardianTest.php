<?php

namespace TimTegeler\Guardian;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GuardianTest extends \PHPUnit_Framework_TestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject|AuthenticationInterface */
    private $authenticator;
    /** @var PHPUnit_Framework_MockObject_MockObject|ServerRequestInterface */
    private $serverRequest;
    /** @var PHPUnit_Framework_MockObject_MockObject|DelegateInterface */
    private $delegate;

    public function setUp()
    {
        $this->authenticator = $this->getMockBuilder(AuthenticationInterface::class)
            ->setMethods(['authenticate'])->getMock();
        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->delegate = $this->getMockBuilder(DelegateInterface::class)->getMock();
    }

    public function testProcessAuthorized()
    {
        # config mocks
        $this->delegate
            ->method('process')
            ->with($this->equalTo($this->serverRequest))
            ->willReturn(new Response());
        $this->authenticator
            ->method('authenticate')
            ->willReturn(true);
        # actual test
        $guardian = new Guardian($this->authenticator);
        $response = $guardian->process($this->serverRequest, $this->delegate);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testProcessDenied()
    {
        # config mocks
        $this->authenticator
            ->method('authenticate')
            ->willReturn(false);
        # actual test
        $guardian = new Guardian($this->authenticator);
        $response = $guardian->process($this->serverRequest, $this->delegate);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
    }

}
