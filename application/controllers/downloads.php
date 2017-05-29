<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* DEZEMBRO DE 2013
*/

class Downloads extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('gercont_model');
	}

  /**
	*
	* index
	* 
	* Lista todos os arquivos para download
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function index(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();

		$this->load->view('includes/header',$data);
		$this->load->view('downloads/listar');
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