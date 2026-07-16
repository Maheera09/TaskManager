<?php
defined ('BASEPATH') or exit("No direct access to scripts");

class AdminModel extends CI_Model{
	protected $table = 'tbl_admin_login';

	public function __construct(){
		parent::__construct();
	}

	public function AdminLogin($email_id, $password){
		$this->db->select('*');
		$this->db->from($this->table);   
		$this->db->where('email_id', $email_id); 
		$this->db->where('password', md5($password));
		$this->db->limit(1);

		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
            return $query->row(); // return the full admin row
        }
        return false;
		}

}
?>
