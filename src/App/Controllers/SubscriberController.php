<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Database\Repositories\SubscriberRepository;
use App\Utils\JsonResponse;
use App\Utils\Validator;
use Exception;

class SubscriberController extends BaseController
{
    /**
     * SubscriberRepository instance
     *
     * @var SubscriberRepository
     */
    protected SubscriberRepository $repository;

    /**
     * Validator instance
     *
     * @var Validator
     */
    protected Validator $validator;

    /**
     * Response instance
     *
     * @var JsonResponse
     */
    protected JsonResponse $response;

    /**
     * Create a new SubscriberController instance
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->repository = new SubscriberRepository($this->db);
        $this->response   = new JsonResponse();
        $this->validator  = new Validator();
    }

    /**
     * Get the sanitized and validated input data
     *
     * @return array
     */
    protected function getInputData()
    {
        try {
            // Get the input data
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate if the input data is valid
            $this->validator->setInputData($data)
                ->setEmailRules()
                ->setNameRules()
                ->setStatusRules()
                ->setOptionalLastNameRules()
                ->validate();

            if (empty($this->validator->getValidatedData())) {
                $this->response->sendJsonResponse(['error' => 'Email, name and status fields are required.'], 400);
                die();
            }

            if (! $this->validator->hasValidationPassed()) {
                $this->response->sendJsonResponse(['error' => $this->validator->getValidationErrors()], 400);
                die();
            }

            $email = $this->validator->getValidatedData()['email'];
            $name = $this->validator->getValidatedData()['name'];
            $lastName = isset($this->validator->getValidatedData()['last_name'])
                ? $this->validator->getValidatedData()['last_name']
                : null;
            $status = $this->validator->getValidatedData()['status'];
        } catch (Exception $e) {
            $this->response->sendJsonResponse(['error' => $e->getMessage()], 400);
            die();
        }
    
        return [$email, $name, $lastName, $status];
    }

    /**
     * Handles mailerlite/v1/subscriber POST request
     * Create a new subscriber and send the json response
     *
     * @return void
     */
    public function createSubscriber()
    {
        try {
            // Get the sanitized and validated input data
            list($email, $name, $lastName, $status) = $this->getInputData();
        
            // Check if the subscriber already exists
            if ($this->repository->subscriberExists($email)) {
                $this->response->sendJsonResponse(['error' => 'Subscriber already exists with email'], 409);
                die();
            }
        
            // Insert the subscriber data
            $this->repository->insertSubscriber($email, $name, $lastName, $status);
        
            $this->response->sendJsonResponse(['status' => 'success'], 201);
        } catch (Exception $e) {
            $this->response->sendJsonResponse(['error' => $e->getMessage()], 400);
        }

        die();
    }

    /**
     * Handles mailerlite/v1/subscribers GET request
     * Get the subscribers and send the json response
     * Also handles pagination
     *
     * @return void
     */
    public function getSubscribers()
    {
        try {
            // Get the page number from the query string
            $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

            // Get the subscribers
            $subscribers = $this->repository->getSubscribers($page, $limit);

            // Get the total count of subscribers
            $totalSubscribers = $this->repository->getSubscriberCount();

            // Calculate the total pages
            $totalPages = ceil($totalSubscribers / $limit);

            // Check if subscribers are found
            if (count($subscribers) > 0) {
                $this->response->sendJsonResponse([
                    'data'     => $subscribers,
                    'paginate' => [
                        'current' => $page,
                        'total'   => $totalPages,
                        'records' => (int) $totalSubscribers,
                    ],
                ], 200);
            } else {
                $this->response->sendJsonResponse(['error' => 'No subscribers found'], 404);
            }
        } catch (Exception $e) {
            $this->response->sendJsonResponse(['error' => $e->getMessage()], 400);
        }

        die();
    }
}
