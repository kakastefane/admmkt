<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* FEVEREIRO DE 2014
*/

class Catalogo extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('gercont_model');
		$this->load->model('catalogo_model');
	}

	public function index(){
		$this->todos();
	}

  /**
	*
	* todos
	* 
	* Lista todos os produtos
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function todos(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['titulo'] = "Catálogo";

		$this->load->view('includes/header',$data);
		$this->load->view('catalogo/listar');
		$this->load->view('includes/footer');

	}

  /**
	*
	* todas_categorias
	* 
	* Lista todos as categorias
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function todas_categorias(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['titulo'] = "Catálogo";

		$this->load->view('includes/header',$data);
		$this->load->view('catalogo/listar_categoria');
		$this->load->view('includes/footer');

	}

  /**
	*
	* todos_produtos
	* 
	* Lista todos as produtos
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function todos_produtos(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['categoria'] = $this->uri->segment(3);
		$this->db->where('url_nome',$data['categoria']);
		$data['titulo'] = "Catálogo > " . $this->db->get('catalogo_categorias')->row()->nome;

		$this->load->view('includes/header',$data);
		$this->load->view('catalogo/listar_produto');
		$this->load->view('includes/footer');

	}

  /**
	*
	* categorias
	* 
	* Lista todos os produtos de uma categoria
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
		$this->db->where('url_nome',$data['categoria']);
		$data['titulo'] = "Catálogo > " . $this->db->get('catalogo_categorias')->row()->nome;

		$this->load->view('includes/header',$data);
		$this->load->view('catalogo/listar');
		$this->load->view('includes/footer');

	}

 

  /**
	*
	* detalhes
	* 
	* Mostra os detalhes de um produto
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function detalhes(){

		$url = $this->uri->segment(3);

		$data = array();

		$data['configuracoes'] 			= $this->gercont_model->configuracoes();
		$data['detalhe']				= $this->gercont_model->detalhes('catalogo_produtos','url_nome',$url,false);
		$data['titulo']					= $data['detalhe']->nome;
		$data['fbimg']					= base_url('uploads/catalogo_produtos/imagens/' . $data['detalhe']->id . '/' . $data['detalhe']->capa);
		$data['categoria'] 				= $this->uri->segment(2);

		if(!$data['detalhe']){
			$this->erro_404();
		} else {
			$this->load->view('includes/header',$data);
			$this->load->view('catalogo/detalhes');
			$this->load->view('includes/footer');
		}

	}

  /**
	*
	* carrinho
	* 
	* Exibi os ítens no carrinho de pedidos
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function carrinho(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['produtos'] = $this->catalogo_model->detalhes_carrinho();
		$data['titulo'] = "Carrinho de Pedidos";

		$this->load->view('includes/header',$data);
		$this->load->view('catalogo/carrinho');
		$this->load->view('includes/footer');

	}

  /**
	*
	* adicionar
	* 
	* Adiciona um produto no carrinho
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function adicionar(){

		$id = $this->uri->segment(4);

		if($this->catalogo_model->adicionar($id)){
			$this->session->set_flashdata('sucesso', 'Produto adicionado com sucesso ao carrinho');
		} else {
			$this->session->set_flashdata('erro', 'Erro ao adicionar o produto ao carrinho');
		}
		redirect($_SERVER['HTTP_REFERER']);

	}

  /**
	*
	* remover
	* 
	* Remove um produto no carrinho
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function remover(){

		$id = $this->uri->segment(4);

		if($this->catalogo_model->remover($id)){
			$this->session->set_flashdata('sucesso', 'Produto removido com sucesso do carrinho');
		} else {
			$this->session->set_flashdata('erro', 'Erro ao remover o produto do carrinho');
		}
		redirect($_SERVER['HTTP_REFERER']);

	}

  /**
	*
	* atualizar
	* 
	* Atualiza o carrinho de compras
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function atualizar(){

		if($this->catalogo_model->atualizar()){
			$this->session->set_flashdata('sucesso', 'Carrinho atualizado com sucesso');
		} else {
			$this->session->set_flashdata('erro', 'Erro ao atualizar o carrinho');
		}
		redirect($_SERVER['HTTP_REFERER']);

	}

  /**
	*
	* finalizar
	* 
	* Atualiza o carrinho de compras e trás o formulário do cliente
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function finalizar(){

		if($this->cart->total_items() <= 0){
			redirect('./catalogo/carrinho');
		}

		$this->catalogo_model->atualizar();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['titulo'] = "Finalizar Pedido";
		$data['js'] = "<script src='./public/codemin/js/jquery.meiomask.js'></script>
		<script src='./public/js/jquery.validate.min.js'></script>";

		$this->load->view('includes/header',$data);
		$this->load->view('catalogo/finalizar');
		$this->load->view('includes/footer');

	}

  /**
	*
	* finalizado
	* 
	* Envia um email com todos os produtos do carrinho e os dados do cliente e grava no banco de dados
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function finalizado(){

		if($this->cart->total_items() <= 0){
			redirect('./catalogo/carrinho');
		}

		// Grava o pedido no banco de dados
		$this->catalogo_model->gravar_pedido();

		if($this->catalogo_model->enviar_pedido()){
			redirect('./catalogo/carrinho/retorno/true');
		} else {
			redirect('./catalogo/carrinho/retorno/false');
		}

	}

  /**
	*
	* limpar
	* 
	* Excluí todos os itens do carrinho
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function limpar(){

		$this->catalogo_model->limpar();
		redirect($_SERVER['HTTP_REFERER']);

	}

  /**
	*
	* retorno
	* 
	* Pagina de rotorno após o envio de email
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/
	public function retorno(){

		$data = array();

		$data['configuracoes'] = $this->gercont_model->configuracoes();
		$data['titulo'] = "Retorno";
		if($this->uri->segment(4) == 'false'){
			$data['retorno'] = 'Erro ao enviar orçamento.';
		}else{
			$data['retorno'] = 'Orçamento enviado com sucesso.';
		}
		

		$this->load->view('includes/header',$data);
		$this->load->view('catalogo/retorno');
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