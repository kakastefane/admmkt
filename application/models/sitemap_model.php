<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap_model extends CI_Model {

	/**
	* registros
	*
	* Retorna um resultado com todas os registros ativos de uma tabela
	* @param string, string
	* @return object
	*
	**/
	public function registros($tabela,$url) {

		$this->db->where('ativo',1);
		$this->db->select_max('codemin_log_acoes.data');
		$this->db->select($tabela . '.' . $url);
		$this->db->join('codemin_log_acoes','codemin_log_acoes.id_registro = ' . $tabela . '.id','INNER');
		$this->db->group_by($tabela . '.id');
		return $this->db->get($tabela)->result();

	}

	/**
	* ultima_modificacao
	*
	* Pega o registro mais recente dos logs de ações
	* @param null
	* @return string
	*
	**/
	public function ultima_modificacao() {

		return $this->db->get('codemin_log_acoes')->row()->data;

	}

}