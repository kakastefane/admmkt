<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* MAIO DE 2013
*/

class Opcoes extends CI_Controller {

	public function adicionar(){

		$this->load->model('codemin_model');

		$tabela = $this->input->post('tabela');
		$campo  = $this->input->post('campo');
		$opcao  = $this->input->post('opcao');
		$tipo  	= $this->input->post('tipo');

		$url = $this->codemin_model->gerar_url_amigavel('codemin_opcoes','opcao',$opcao);

		$inserir = array(
			'tabela' 		=> $tabela,
			'campo'  		=> $campo,
			'opcao'  		=> $opcao,
			'url_opcao'	=> $url
		);
		$this->db->insert('codemin_opcoes',$inserir);

		$return['tipo'] 	= $tipo;
		$return['valor'] 	= $opcao;
		$return['id'] 		= $this->db->insert_id();

		echo json_encode($return);

	}

	public function remover(){

		$id = $this->input->post('id');

		$this->db->where('id',$id);
		if($this->db->delete('codemin_opcoes')){
			$return['success'] = true;
		} else {
			$return['success'] = false;
		}

		echo json_encode($return);

	}

	public function ordenar(){

		$this->load->model('codemin_model');

		$tabela 		= $this->uri->segment(3);
		$registros 	= $this->input->post('registros');
		$resultado 	= $this->codemin_model->ordenar_registros($tabela,$registros);
		echo json_encode($resultado);

	}

	public function videos(){

		if(isset($_POST['video'])){

			$video = $this->input->post('video');
			$video = verificar_video($video);

			switch ($video['tipo']) {
				case 'youtube':
					$return['link'] = "<a target='_blank' href='http://youtu.be/" . $video['link'] . "'>http://youtu.be/" . $video['link'] . "</a>";
					$return['capa'] = "<img src='http://img.youtube.com/vi/" . $video['link'] . "/1.jpg' />";
					break;

				case 'vimeo':
					$img = miniatura_video('http://vimeo.com/'.$video['link']);
					$return['link'] = "<a target='_blank' href='http://vimeo.com/" . $video['link'] . "'>http://vimeo.com/" . $video['link'] . "</a>";
					$return['capa'] = "<img src='$img[thumb]' />";
					break;
			}

			$tabela 	= $this->input->post('tabela');
			$campo  	= $this->input->post('campo');
			$registro = $this->input->post('id');
			$tipo  		= $this->input->post('tipo');

			$inserir = array(
				'tabela' 		=> $tabela,
				'campo'  		=> $campo,
				'registro' 	=> $registro,
				'tipo' 			=> $video['tipo'],
				'video'			=> $video['link']
			);
			$this->db->insert('codemin_videos',$inserir);

			$id = $this->db->insert_id();
			$return['botao'] = "<button class='btn btn-danger'>Excluir<input type='hidden' value='$id'></button>";

			echo json_encode($return);

		} elseif(isset($_POST['id'])){

			$this->db->where('id',$this->input->post('id'));
			$return['deletado'] = $this->db->delete('codemin_videos');
			echo json_encode($return);

		}

	}

	public function atualizar_legenda(){

		$retorno = array();
		$this->db->where('id',$this->input->post('id'));
		$retorno['sucesso'] = $this->db->update('codemin_uploads',array('legenda' => $this->input->post('legenda')));
		echo json_encode($retorno);

	}

}