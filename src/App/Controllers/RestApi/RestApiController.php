<?php

namespace App\Controllers\RestApi;

use App\Utils\Request;

class RestApiController
{
    /**
     * Request instance
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Handle the rest api request
     *
     * @return void
     */
    public function handleRequest(Request $request): void
    {
        $this->request = $request;
        $this->request->run();
    }
}
