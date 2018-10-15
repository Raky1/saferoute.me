<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Occurrence_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function insert($occurrence) {
        $this->db->insert('tb_occurrence', $occurrence);
        if ($this->db->affected_rows() == 1) return $this->db->insert_id();

        return false;
    }

    public function getAll() {
        $query = $this->db->query('SELECT * FROM tb_occurrence;');
		return $query->result_array();
    }

    public function getOccurrence($id) {
        $query = $this->db->query('SELECT * FROM tb_occurrence WHERE id=? LIMIT 1;', array($id));

		return $query->result_array();
    }

    public function report($report) {
        $this->db->insert('tb_occurrence_report', $report);
        if ($this->db->affected_rows() == 1) return $this->db->insert_id();

        return false;
    }
	
}
