<?php

class Login extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('User_model');
	}

	public function index(){
		$data['title'] = 'tiket.com | Hotel, Pesawat, Kereta Api, Sewa Mobil, Konser';
		$this->load->view('templates/header', $data);
		if($this->session->userdata('username') == NULL){
			$this->load->view('templates/login_navbar');
		}else{
			$this->load->view('templates/default_navbar');			
		}
		$this->load->view('login/index');
		$this->load->view('templates/footer_login');
	}

	public function validate(){
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');

		if($this->form_validation->run() == FALSE){
			redirect(base_url('/Login'));
			
		}else if($this->User_model->validate($this->input->post('email'), $this->input->post('password')) == TRUE && $this->User_model->isAdmin($this->input->post('email')) == FALSE){
			$this->session->set_userdata('username', $this->User_model->search_by_email($this->input->post('email'))->username);
			$this->session->set_userdata('email',$this->input->post('email'));
			redirect(base_url());
		}else if($this->User_model->validate($this->input->post('email'), $this->input->post('password')) == TRUE && $this->User_model->isAdmin($this->input->post('email')) == TRUE){
			$this->session->set_userdata('username', $this->User_model->search_by_email($this->input->post('email'))->username);
			$this->session->set_userdata('email',$this->input->post('email'));
			$this->session->set_userdata('admin',1);
			redirect(base_url());
		}
		else{
			redirect(base_url('/Login'));
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}
}
?>