<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogo_model extends CI_Model {

	private $configuracoes = array();

	/**
	*
	* Função construct, descrever as funções caso tenha
	* @param null
	* @return null
	*
	**/
	public function __construct() {
		parent::__construct();
	}

	/**
	* adicionar
	*
	* Adiciona um produto ao carrinho de compras
	* @param int
	* @return boolean
	*
	**/
	public function adicionar($id) {

		$this->db->limit(1);
		$this->db->where('ativo',1);
		$this->db->where('id',$id);
		$produto = $this->db->get('catalogo_produtos')->row();
		if(!$produto){
			return false;
		}

		$data = array(
			'id'      => $produto->id,
			'qty'     => 1,
			'price'   => $produto->valor,
			'name'    => $produto->url_nome
		);
		return $this->cart->insert($data);

	}

	/**
	* remover
	*
	* Remove um produto do carrinho de compras
	* @param string
	* @return boolean
	*
	**/
	public function remover($id) {

	 $data = array(
		    'rowid'   => $id,
		    'qty'     => 0
		);
		return $this->cart->update($data);

	}

	/**
	* atualizar
	*
	* Limpa o carrinho de compras
	* @param null
	* @return boolean
	*
	**/
	public function atualizar() {

		$resultado = $this->input->post();
		$update = array();
		foreach ($resultado as $rowid => $qtd) {
			$update[] = array(
				'rowid' => $rowid,
				'qty' => $qtd
			);
		}
		return $this->cart->update($update); 

	}

	/**
	* gravar_pedido
	*
	* Grava o pedido no banco de dados
	* @param null
	* @return boolean
	*
	**/
	public function gravar_pedido() {

		$insert = array(
			'nome' => $this->input->post('nome'),
			'email' => $this->input->post('email'),
			'telefone' => $this->input->post('telefone'),
			'observacoes' => $this->input->post('observacoes'),
			'total' => $this->cart->total(),
			'data_hora' => date('Y-m-d H:i:s')
		);
		$this->db->insert('catalogo_pedidos',$insert);

		$id_pedido = $this->db->insert_id();
		$produtos = $this->detalhes_carrinho();

		$pedidos = array();
		foreach ($produtos as $produto) {
			$pedidos[] = array(
				'id_pedido' => $id_pedido,
				'id_produto' => $produto['id'],
				'nome' => $produto['nome'],
				'quantidade' => $produto['qty'],
				'preco' => $produto['price'],
				'subtotal' => $produto['subtotal']
			);
		}
		return $this->db->insert_batch('catalogo_pedidos_produtos',$pedidos);

	}

	/**
	* enviar_pedido
	*
	* Envia o pedido por email
	* @param null
	* @return boolean
	*
	**/
	public function enviar_pedido() {

		$email = $this->input->post('email');

		$data['cliente'] = $this->input->post();
		$data['produtos'] = $this->detalhes_carrinho();
		$menssagem = $this->load->view('catalogo/email',$data,TRUE);

		$retorno = array();

		$destinatarios = $this->db->get('catalogo_configuracao')->row()->email;
		$assunto = "[SITE] Novo Pedido";

		// Limpa o carrinho
		$this->limpar();

		return enviar_email($destinatarios,$assunto,$menssagem,$email);

	}

	/**
	* listar_pedidos
	*
	* Lista todos os pedidos feitos no site
	* @param null
	* @return objetc
	*
	**/
	public function listar_pedidos($pagina = 1) {

		$por_pagina = 50;

		if($pagina == 1){
			$incial = 0;
		} else {
			$incial = $por_pagina*($pagina-1);
		}

		$this->load->library('pagination');
		$config['base_url'] = './administrador/catalogo_listar_pedidos/pagina/';
		$config['uri_segment'] = 4;
	  $config['total_rows'] = $this->db->count_all('codemin_contatos');
	  $config['per_page'] = $por_pagina;
	  $config['use_page_numbers'] = TRUE;
	  $this->pagination->initialize($config);

		$this->db->limit($por_pagina,$incial);

		$this->db->order_by('data_hora','desc');
		return $this->db->get('catalogo_pedidos')->result();

	}

	/**
	* detalhes_pedido
	*
	* Lista os detalhes de um pedido
	* @param int
	* @return object
	*
	**/
	public function detalhes_pedido($id) {

		$this->db->where('id',$id);
		$pedido = $this->db->get('catalogo_pedidos')->row();

		if($pedido->visualizado == 0){
			$this->db->where('id',$id);
			$update = array('visualizado' => 1);
			$this->db->update('catalogo_pedidos',$update);
		}

		return $pedido;

	}

	/**
	* listar_produtos_pedido
	*
	* Lista os produtos de um pedido
	* @param int
	* @return object
	*
	**/
	public function listar_produtos_pedido($id) {

		$this->db->where('id_pedido',$id);
		return $this->db->get('catalogo_pedidos_produtos')->result();

	}

	/**
	* limpar
	*
	* Limpa o carrinho de compras
	* @param null
	* @return boolean
	*
	**/
	public function limpar() {
		return $this->cart->destroy();
	}

	/**
	* detalhes_carrinhos
	*
	* Trás os produtos do carrinho
	*
	* @param null
	* @return boolean
	*
	**/
	public function detalhes_carrinho() {

		$itens = array();

		foreach ($this->cart->contents() as $produto) {
			$this->db->select('nome, capa');
			$this->db->where('id',$produto['id']);
			$resultado = $this->db->get('catalogo_produtos')->row();
			$produto['nome'] = $resultado->nome;
			$produto['img'] = "./uploads/catalogo_produtos/imagens/" . $produto['id'] . "/mini_thumbs/" . $resultado->capa;
			$itens[] = $produto;
		}

		return $itens;

	}

	/**
	* pegar_novos_pedidos
	*
	* Pega os novos pedidos (não visualizados)
	* @param int
	* @return object
	*
	**/
	public function pegar_novos_pedidos($limit) {

		$this->db->limit($limit);
		$this->db->where('visualizado',0);
		$this->db->order_by('data_hora','DESC');
		return $this->db->get('catalogo_pedidos')->result();

	}

}