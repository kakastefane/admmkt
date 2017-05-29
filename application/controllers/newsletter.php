<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* DEZEMBRO DE 2013
*/

class Newsletter extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('gercont_model');
	}

  /**
	*
	* form
	* 
	* Exibi o formulário para cadastro do newsletter
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function form(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['contato'] = $this->gercont_model->contato();
		$data['titulo'] = 'Newsletter';
		$data['js'] = "<script src='./public/js/jquery.validate.min.js'></script>";

		$this->load->view('includes/header',$data);
		$this->load->view('newsletter/form');
		$this->load->view('includes/footer');

	}

  /**
	*
	* enviar
	* 
	* Recebe os dados do formulários por ajax e grava no banco de dados
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function enviar(){

		$retorno = array();

		$nome 		= $this->input->post('nome');
		$email 		= $this->input->post('email');

		$this->db->where('email',$email);
		if($this->db->count_all_results('codemin_newsletter') > 0){			
			$retorno['sucesso'] = false;
		} else {
			$insert = array(
				'nome' => $nome,
				'email' => $email,
				'data_hora' => date('Y-m-d H:i:s')
			);
			$retorno['sucesso'] = $this->db->insert('codemin_newsletter',$insert);
		}
		$converted_sucesso = ($retorno['sucesso']) ? 'true' : 'false';		
		redirect('/newsletter/resposta/'.$converted_sucesso);		
	}

	/**
	*
	* resposta
	* 
	* Resposta se foi cadastrado a newsletter
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function resposta(){

		$retorno = $this->uri->segment(3);
		
		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['titulo'] = "Newsletter";
		$data['titulo_retorno'] = $retorno == 'true' ? 'E-mail cadastrado com sucesso em nossa newsletter.' : 'E-mail ja cadastrado em nossa newsletter.';

		$this->load->view('includes/header',$data);
		$this->load->view('newsletter/resposta');
		$this->load->view('includes/footer');

	}

  /**
	*
	* erro_404
	* 
	* Exibi a página não encontrada
	* 
	*
	* @access	private
	* @param	null
	* @return	null
	*/
	private function erro_404(){

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['titulo'] = "Página não encontrada";

		$this->load->view('includes/header',$data);
		$this->load->view('erro_404');
		$this->load->view('includes/footer');

	}

}