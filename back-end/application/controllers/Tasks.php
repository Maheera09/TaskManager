<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller
{

	public function __construct(){
		parent::__construct();
		$this->load->model('TaskModel');
	}

	private function require_login()
{
    $session_data = $this->session->userdata('logged_in');

    if (!$session_data) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['status' => false, 'message' => 'Not authenticated']);
        exit(); // stop execution immediately
    }

    return $session_data;
}
    public function index()
    {
        header('Content-Type: application/json');

        $session_data = $this->require_login(); // will exit() automatically if not logged in
    	$admin_id = $session_data['admin_id'];
		
		$tasks = $this->TaskModel->getAllTasks($admin_id);

		echo json_encode([
            'status' => true,
            'data' => $tasks
        ]);
    }
}
