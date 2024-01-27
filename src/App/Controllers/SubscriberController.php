<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Database\Repositories\SubscriberRepository;

class SubscriberController extends BaseController {
	private $repository;

	public function __construct() {
		parent::__construct();

		$this->repository = new SubscriberRepository($this->db);
	}

	protected function hasValidInputs( $data ) {
		if ( !isset($data['email']) || !isset($data['name']) || !isset($data['status']) ) {
			return false;
		}

		if ( empty(trim($data['email'])) || empty(trim($data['name'])) || empty(trim($data['status'])) ) {
			return false;
		}

		return true;
	}

	private function getInputData() {
		// Get the input data
		$data = json_decode(file_get_contents('php://input'), true);

		if ( ! $this->hasValidInputs( $data ) ) {
			http_response_code(400);
			echo json_encode(['error' => 'Email, name and status fields are required.']);
			exit;
		}

		// Sanitize and validate the data
		$email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
		
		// htmlspecialchars function to convert special characters to their HTML entities, which can prevent XSS (Cross-Site Scripting) attacks
		// This will convert special characters like <, >, &, ", and ' to their HTML entities (&lt;, &gt;, &amp;, &quot;, and &#039; respectively),
		// which can prevent these characters from being interpreted as HTML tags or entities.
		$name = htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8');
		$lastName = isset($data['last_name']) ? htmlspecialchars(trim($data['last_name']), ENT_QUOTES, 'UTF-8') : null;
		$status = htmlspecialchars(trim($data['status']), ENT_QUOTES, 'UTF-8');
	
		// Validate the email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			http_response_code(400);
			echo json_encode(['error' => 'Please provide a valid email address.']);
			exit;
		}

		// Validate the status
		if ( !in_array($status, ['active', 'inactive'])) {
			http_response_code(400);
			echo json_encode(['error' => 'Status must be active or inactive.']);
			exit;
		}
	
		return [$email, $name, $lastName, $status];
	}

	public function createSubscriber() {
		// Get the sanitized and validated input data
		list($email, $name, $lastName, $status) = $this->getInputData();
	
		// Check if the subscriber already exists
		if ($this->repository->subscriberExists($email)) {
			http_response_code(409);
			echo json_encode(['error' => 'Subscriber already exists with email ' . $email ]);
			exit;
		}
	
		// Insert the subscriber data
		$this->repository->insertSubscriber($email, $name, $lastName, $status);
	
		http_response_code(201);
		echo json_encode(['status' => 'success']);
	}

	public function getSubscribers() {
		// Get the page number from the query string
		$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

		// Get the subscribers
		$subscribers = $this->repository->getSubscribers( $page, $limit );

		// Get the total count of subscribers
		$totalSubscribers = $this->repository->getSubscriberCount();

		// Calculate the total pages
		$totalPages = ceil($totalSubscribers / $limit);

		// Check if subscribers are found
		if (count($subscribers) > 0) {
			http_response_code(200);
			echo json_encode([
				'data'     => $subscribers,
				'paginate' => [
					'current' => $page,
					'total'   => $totalPages,
					'records' => (int) $totalSubscribers,
				],
			]);
		} else {
			http_response_code(404);
			echo json_encode($subscribers);
		}

		exit;
	}
}
