<?php 
defined ('BASEPATH') OR exit('No direct access to scripts');

class TaskModel extends CI_Model{

	protected $table = 'tbl_tasks';
	
	public function __construct(){
		parent::__construct();
	}

	public function getAllTasks ($admin_id){
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('admin_id', $admin_id);
		$this->db->order_by('created_at', 'DESC');

		$query = $this->db->get();
		return $query->result();
	}

	public function getTask($task_id, $admin_id){
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('admin_id', $admin_id);
		$this->db->where('task_id', $task_id);
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows()==1){
			return $query->row();	
		}
		return false;
	}

	public function toggleStatus($task_id, $admin_id){
		$task = $this->getTask($task_id, $admin_id);

		if (!$task) {
            return false;
        }

		$new_status = ($task->status == 'pending' ? 'completed' : 'pending');

		$this->db->where('admin_id', $admin_id);
		$this->db->where('task_id', $task_id);
		return $this->db->update($this->table, ['status' => $new_status]);
	}


	public function createTask($data){
		$inserted = $this->db->insert($this->table, $data);

		if ($inserted){
			return $this->db->insert_id(); //return the new task ID
		}
		return false;
	}

	public function updateTask($task_id, $admin_id, $data){
		$this->db->where('task_id', $task_id);
		$this->db->where('admin_id', $admin_id);
		return $this->db->update($this->table, $data);
	}

	public function deleteTask($task_id, $admin_id){
		$this->db->where('task_id', $task_id);
		$this->db->where('admin_id', $admin_id);
		return $this->db->delete($this->table);
	}



}


?>
