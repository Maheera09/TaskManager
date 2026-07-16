<?php
defined ('BASEPATH') or exit ('No direct access to scripts');

class  Admin extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('AdminModel');
	}

	public function login(){
		header('content-type: application/json');

		// Read the raw JSON body instead of $_POST
$json_input = json_decode(file_get_contents('php://input'), true);

$email_id = isset($json_input['email_id']) ? trim($json_input['email_id']) : '';
$password = isset($json_input['password']) ? trim($json_input['password']) : '';

// Manual validation since form_validation only reads $_POST, not JSON bodies
if (empty($email_id) || !filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => false,
        'message' => 'A valid email is required'
    ]);
    return;
}

if (empty($password)) {
    echo json_encode([
        'status' => false,
        'message' => 'Password is required'
    ]);
    return;
}

		$admin = $this->AdminModel->AdminLogin($email_id, $password);

		if ($admin){
			$session_data = array (
				'admin_id' => $admin->admin_id, 
				'admin_username'=>$admin->admin_username,
				'is_admin'=>$admin->is_admin,
			);
			$this->session->set_userdata('logged_in', $session_data);
			
			echo json_encode([
                'status' => true,
                'message' => 'Login successful',
                'data' => $session_data,
            ]);
			} else {
				echo json_encode([
					'status' =>false,
					'message' => 'Invalid email or password',
				]);
			}

	}

	public function logout(){
		header('content-type: application/json');

		$this->session->sess_destroy();
		
		echo json_encode([
			'status' => true, 
			'message' => 'Logged out Successfully'
		]);
	}

	 public function dashboard()
    {
        header('Content-Type: application/json');

        $session_data = $this->session->userdata('logged_in');

        if (!$session_data) {
            http_response_code(401);
            echo json_encode([
                'status' => false,
                'message' => 'Not authenticated'
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'data' => $session_data
        ]);
    }
}
?>
