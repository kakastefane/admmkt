<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicial extends CI_Controller {

	// Deixar como função como private após a instalação
	public function index(){
	// private function index(){

		$this->load->model('codemin_model');

		$data = array();
		if($_POST && sizeof($this->db->list_tables()) == 0){
			$data['resultado'] = $this->codemin_model->instalar();
		}
		$this->load->view('codemin/inicial/instalacao',$data);

	}

}