<?php

namespace App\Controllers\RestApi;

use App\Utils\Request;

class RestApiController
{
    /**
     * Create a new RestApiController instance
     *
     */
    public function __construct(protected Request $request)
    {
    }

    /**
     * Handle the rest api request
     *
     * @return void
     */
    public function handleRequest(): void
    {
        $this->request->run();
    }
}
