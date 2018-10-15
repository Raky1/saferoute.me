<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {

	public function index() {
		$this->load->library('session');
		$this->config->load('recaptcha');

		$this->load->view('map');
	}

	public function json($only_occurrences = false) {
		$this->load->model('occurrence_model');
		$occurrences = $this->occurrence_model->getAll();

		$result = true;
		if(count($occurrences) == 0) {
			$result = false;
		}

		$response = array(
			'result'		=> $result,
			'occurrences'	=> $occurrences
		);

		if($only_occurrences == false) {
			$this->load->model('marker_model');
			$markers = $this->marker_model->getAll();

			$response['markers'] = $markers;
		}

		echo json_encode($response);
	}
}
