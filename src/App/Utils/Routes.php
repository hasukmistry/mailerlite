<?php

namespace App\Utils;

class Routes
{
    /**
     * The namespace for the API
     *
     * @var string
     */
    const API_NAMESPACE = 'mailerlite/v1';

    /**
     * Get all defined routes
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        return [
            self::API_NAMESPACE . '/subscriber' => [
                'method'   => 'POST',
                'callback' => [
                    \App\Controllers\SubscriberController::class,
                    'createSubscriber'
                ]
            ],
            self::API_NAMESPACE . '/subscribers' => [
                'method'   => 'GET',
                'callback' => [
                    \App\Controllers\SubscriberController::class,
                    'getSubscribers'
                ]
            ],
        ];
    }
}
