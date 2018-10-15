<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marker_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function insert() {

    }

    public function getAll() {
        $query = $this->db->query('SELECT * FROM tb_marker;');
		return $query->result_array();
    }

    public function getMarker($id) {

    }
	
}
