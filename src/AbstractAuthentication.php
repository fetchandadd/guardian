<?php

namespace TimTegeler\Guardian;

use GuzzleHttp\Psr7\Response;

/**
 * Class AbstractAuthentication
 * @package TimTegeler\Guardian
 */
abstract class AbstractAuthentication implements AuthenticationInterface
{

    /**
     * @return Response
     */
    public function getAuthenticationFailedResponse()
    {
        return (new Response())->withStatus(403);
    }
}