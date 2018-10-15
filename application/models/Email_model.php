<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function insert($email) {
        $this->db->insert('tb_email', array('email' => $email));
        if ($this->db->affected_rows() == 1) return $this->db->insert_id();

        return 0;
    }

    public function verify($email) {
        $query = $this->db->query('SELECT * FROM tb_email WHERE email=? LIMIT 1;', array($email));
        if ($query->num_rows() == 0) return 0;

        $result = $query->row_array();
        return $result['id'];
    }
	
}
