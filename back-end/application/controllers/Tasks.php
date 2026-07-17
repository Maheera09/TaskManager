<?php

use PharIo\Manifest\Application;
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

	public function view($task_id = null){
		header ('content-type: Application/json');

		$session_data = $this->require_login();
		$admin_id = $session_data['admin_id'];
		 if (!task_id){
			echo json_encode([
				'status' => false,
				'message' => 'Task ID is required',
			]);
			return;
		 }

		 $task = $this->TaskModel->getTask($task_id, $admin_id);

		 if ($task){
			echo json_encode(['status' => true, 'data' => $task ]);
		 }else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Task not found']);
        }
	}

	public function create(){
		header ('content-type: Application/json');

		$session_data = $this->require_login();
		$admin_id = $session_data['admin_id'];

		$json_input = json_decode(file_get_contents('php://input'), true);

		$title = isset($json_input['title']) ? trim($json_input['title']):'';
		$description = isset($json_input['description']) ? trim($json_input[title]) : '';
		$due_date = isset($json_input['due_date']) ? trim($json_input['due_date']) : "";

		if (empty($title)) {
			 echo json_encode(['status' => false, 'message' => 'Title is required']);
            return;
		}

		$data = array(
			'admin_id' => $admin_id,
			'title' => $title,
 			'description' => $description,
            'due_date' => $due_date,
            'status' => 'pending'
		);

		$new_task_id = $this->TaskModel->createTask($data);

        if ($new_task_id) {
            echo json_encode([
                'status' => true,
                'message' => 'Task created successfully',
                'data' => ['task_id' => $new_task_id]
            ]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to create task']);
        }
	}

	public function update($task_id = null)
    {
        header('Content-Type: application/json');

        $session_data = $this->require_login();
        $admin_id = $session_data['admin_id'];

        if (!$task_id) {
            echo json_encode(['status' => false, 'message' => 'Task ID is required']);
            return;
        }

        $json_input = json_decode(file_get_contents('php://input'), true);

        // Confirm the task exists and belongs to this admin before updating
        $existing = $this->TaskModel->getTask($task_id, $admin_id);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Task not found']);
            return;
        }

        $data = array();
        if (isset($json_input['title'])) $data['title'] = trim($json_input['title']);
        if (isset($json_input['description'])) $data['description'] = trim($json_input['description']);
        if (isset($json_input['due_date'])) $data['due_date'] = trim($json_input['due_date']);
        if (isset($json_input['status'])) $data['status'] = trim($json_input['status']);

        if (empty($data)) {
            echo json_encode(['status' => false, 'message' => 'No fields to update']);
            return;
        }

        $updated = $this->TaskModel->updateTask($task_id, $admin_id, $data);

        if ($updated) {
            echo json_encode(['status' => true, 'message' => 'Task updated successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to update task']);
        }
    }

	public function delete($task_id = null)
    {
        header('Content-Type: application/json');

        $session_data = $this->require_login();
        $admin_id = $session_data['admin_id'];

        if (!$task_id) {
            echo json_encode(['status' => false, 'message' => 'Task ID is required']);
            return;
        }

        $existing = $this->TaskModel->getTask($task_id, $admin_id);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Task not found']);
            return;
        }

        $deleted = $this->TaskModel->deleteTask($task_id, $admin_id);

        if ($deleted) {
            echo json_encode(['status' => true, 'message' => 'Task deleted successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to delete task']);
        }
    }

	public function toggle($task_id = null)
    {
        header('Content-Type: application/json');

        $session_data = $this->require_login();
        $admin_id = $session_data['admin_id'];

        if (!$task_id) {
            echo json_encode(['status' => false, 'message' => 'Task ID is required']);
            return;
        }

        $toggled = $this->TaskModel->toggleStatus($task_id, $admin_id);

        if ($toggled) {
            echo json_encode(['status' => true, 'message' => 'Task status toggled']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to toggle task status']);
        }
    }
}
