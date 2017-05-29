<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* FEVEREIRO DE 2014
*/

class Videos extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('gercont_model');
	}

	public function index(){
		$this->todos();
	}

  /**
	*
	* todos
	* 
	* Lista todos os videos
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function todos(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['titulo'] = "Videos";

		$this->load->view('includes/header',$data);
		$this->load->view('videos/listar');
		$this->load->view('includes/footer');

	}

  /**
	*
	* categorias
	* 
	* Lista todos os videos de uma categoria
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function categorias(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['categoria'] = $this->uri->segment(3);
		$this->db->where('url_opcao',$data['categoria']);
		$data['titulo'] = "Videos > " . $this->db->get('codemin_opcoes')->row()->opcao;

		$this->load->view('includes/header',$data);
		$this->load->view('videos/listar');
		$this->load->view('includes/footer');

	}

  /**
	*
	* detalhes
	* 
	* Mostra os detalhes de um vídeo
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function detalhes(){

		$url = $this->uri->segment(2);

		$data = array();

		$data['configuracoes'] 	= $this->gercont_model->configuracoes();
		$data['detalhe']				= $this->gercont_model->detalhes('videos','url_titulo',$url);
		$data['titulo']					= $data['detalhe']->titulo;

		if(!$data['detalhe']){
			$this->erro_404();
		} else {
			$this->load->view('includes/header',$data);
			$this->load->view('videos/detalhes');
			$this->load->view('includes/footer');
		}

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