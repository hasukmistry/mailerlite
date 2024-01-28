<?php

namespace App\Utils;

class JsonResponse
{
    /**
     * Set the response headers for the rest api
     *
     * @return self
     */
    public function setResponseHeaders(): self
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        //phpcs:ignore Generic.Files.LineLength.TooLong
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        return $this;
    }

    /**
     * Set the headers for the preflight request
     *
     * @return self
     */
    public function setPreflightResponseHeaders(): self
    {
        header('HTTP/1.1 200 OK');

        return $this;
    }

    /**
     * Send a json response
     *
     * @param array $data         The data to send
     * @param integer $statusCode The status code to send
     *
     * @return void
     */
    public function sendJsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($data);
    }

    /**
     * Terminate the response stream
     *
     * @return void
     */
    public function terminateResponseStream()
    {
        exit();
    }
}
