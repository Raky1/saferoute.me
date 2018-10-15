<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Occurrence extends CI_Controller {

	public function index() {
		$this->load->library('session');
		$this->config->load('recaptcha');

		$this->load->model('marker_model');

		$data = array(
			'markers' => $this->marker_model->getAll()
		);

		$this->load->view('fui_roubado', $data);
	}

	//==================================================================================
	public function insert() {
		$this->load->library('session');

		//recaptcha
		$this->config->load('recaptcha');

		$recaptcha_data = $this->input->post('g-recaptcha-response');
		if($recaptcha_data === null) $this->showError('invalid recaptcha');

		$secret_key = $this->config->item('recaptcha')['server_key'];
		$response = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$recaptcha_data.'&remoteip='.$_SERVER['REMOTE_ADDR']));            

		if (!$response->success) $this->showError('invalid recaptcha');

		//--------- form ----------
		$this->load->model('util/validation');

		$occurrence = [];

		$address= $this->input->post('address');
		$address = $this->validation->valid_string_post($address, 1, 255);
		if ($address === null) $this->showError('invalid address');

		$address = explode('-',$address);
		$occurrence['road'] = $address[0];
		$occurrence['state'] = $address[1];
		$occurrence['city'] = $address[2];
		$occurrence['country'] = $address[3];
		
		$occurrence['latitude'] = $this->input->post('latitude');
		$occurrence['latitude'] = $this->validation->valid_string_post($occurrence['latitude']);
		if ($occurrence['latitude'] === null) $this->showError('invalid latitude');

		$occurrence['longitude'] = $this->input->post('longitude');
		$occurrence['longitude'] = $this->validation->valid_string_post($occurrence['longitude']);
		if ($occurrence['longitude'] === null) $this->showError('invalid longitude');

		$occurrence['occurrence_day'] = $this->input->post('day');
		$occurrence['occurrence_day'] = $this->validation->valid_date_post($occurrence['occurrence_day']);
		if ($occurrence['occurrence_day'] === null) $this->showError('invalid day');

		$occurrence['occurrence_time'] = $this->input->post('time');
		$occurrence['occurrence_time'] = $this->validation->valid_time_post($occurrence['occurrence_time']);
		if ($occurrence['occurrence_time'] === null) $this->showError('invalid time');

        
		$occurrence['marker_id'] = (int)$this->input->post('marker_id');
		$occurrence['marker_id'] = $this->validation->valid_int_post($occurrence['marker_id'], 1, 2);
		if ($occurrence['marker_id'] === null) $this->showError('invalid marker');

		
		$occurrence['reported'] = (int)$this->input->post('reported');
		$occurrence['reported'] = $this->validation->valid_int_post($occurrence['reported'], 0, 1);
		if ($occurrence['reported'] === null) $this->showError('invalid reported');

		$occurrence['aggression'] = (int)$this->input->post('aggression');
		$occurrence['aggression'] = $this->validation->valid_int_post($occurrence['aggression'], 0, 1);
		if ($occurrence['aggression'] === null) $this->showError('invalid aggression');

		$occurrence['complement'] = $this->input->post('complement');
		$occurrence['complement'] = $this->validation->valid_string_post($occurrence['complement'], 0, 255);
		//if ($occurrence['complement'] === null) $this->showError('invalid complement');

		$email = $this->input->post('email');
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->showError('invalid email');

		$this->load->model('email_model');
		$result = $this->email_model->verify($email);
		if ($result === 0) {
			$result = $this->email_model->insert($email);
			if ($result === 0) $this->showError('error verify email');
		}

		$occurrence['email_id'] = $result;

		$this->load->model('occurrence_model');

		//insert occurrence
		$result = $this->occurrence_model->insert($occurrence);
		if ($result === false) $this->showError('error register occurrence');

		$this->session->set_flashdata('msg', 'Occurrence registred');

		redirect('occurrence');
	}

	//==================================================================================
	public function report() {

		//recaptcha
		$this->config->load('recaptcha');

		$recaptcha_data = $this->input->post('g-recaptcha-response');
		if($recaptcha_data === null) $this->ErrorJSON('invalid recaptcha');

		$secret_key = $this->config->item('recaptcha')['server_key'];
		$response = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$recaptcha_data.'&remoteip='.$_SERVER['REMOTE_ADDR']));            

		if (!$response->success) $this->ErrorJSON('invalid recaptcha');

		// data validation

		$this->load->model('util/validation');

		$report =[];

		// email verify
		$email = $this->input->post('email');
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->ErrorJSON('invalid email');

		$this->load->model('email_model');
		$result = $this->email_model->verify($email);
		if ($result === 0) {
			$result = $this->email_model->insert($email);
			if ($result === 0) $this->ErrorJSON('error verify email');
		}

		$report['email_id'] = $result;

		//id occurrence
		$report['occurrence_id'] = (int)$this->input->post('occurrence_id');
		$report['occurrence_id'] = $this->validation->valid_int_post($report['occurrence_id']);
		if ($report['occurrence_id'] === null) $this->ErrorJSON('invalid occurrence id');

		//note
		$report['note'] = $this->input->post('note');
		$report['note'] = $this->validation->valid_string_post($report['note'], 0, 255);
		if ($report['note'] === null) $this->ErrorJSON('invalid note');

		
		//insert occurrence
		$this->load->model('occurrence_model');

		$result = $this->occurrence_model->report($report);
		if ($result === false) $this->ErrorJSON('error register report');

		$data = array(
			'result' => true
		);
		echo json_encode($data);

	}
	
	//==================================================================================
	public function teste() {
		$data = '12t22tt';

		$this->load->model('util/validation');

		$r = $this->validation->valid_int_post((int)$data, 1, 2);

		var_dump($r);
	}
	

	//==================================================================================
	private function showError($msg = '') {
		//getall datapost
		$post_data = array(
			'address' => $this->input->post('address'),
			'latitude' => $this->input->post('latitude'),
			'longitude' => $this->input->post('longitude'),
			'day' => $this->input->post('day'),
			'time' => $this->input->post('time'),
			'marker_id' => $this->input->post('marker_id'),
			'reported' => $this->input->post('reported'),
			'aggression' => $this->input->post('aggression'),
			'complement' => $this->input->post('complement'),
			'email' => $this->input->post('email')
		);

		$this->session->set_flashdata('occurrence', $post_data);

		$this->session->set_flashdata('error', $msg);
		redirect('occurrence');
	}

	//==================================================================================
	private function ErrorJSON($msg = '') {
		$error = array(
			'msg' => $msg
		);

		$data = array(
			'result' => false,
			'error' => $error
		);

		echo json_encode($data);
		die();
	}
}
