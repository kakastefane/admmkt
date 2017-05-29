<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gercont {

	/**
	*
	* __get
	*
	* Enables the use of CI super-global without having to define an extra variable.
	*
	*
	* @access	public
	* @param	$var
	* @return	mixed
	*/
	public function __get($var){
	  return get_instance()->$var;
	}

	/**
	*
	* listagem
	*
	* Faz a listagem de noticias
	*
	*
	* @access	public
	* @param	$tabela,
	* @return	mixed
	*/
	public function listagem($params){

		$use_idioma = true;
		$use_ativo = true;
		$uri_segment = 2;
		$quebra_linha = 'primeira';

		if(isset($params['quebra_linha']))
			$quebra_linha = $params['quebra_linha'];

		if(isset($params['uri_segment']))
			$uri_segment = $params['uri_segment'];

		if(isset($params['idioma']))
			$use_idioma = $params['idioma'];

		if(isset($params['ativo']))
			$use_ativo = $params['ativo'];

		if(isset($params['tabela']))
			$tabela = $params['tabela'];

		if(isset($params['layout']))
			$layout = $params['layout'];

		if(isset($params['links']))
			$links = $params['links'];

		if(isset($params['limit']))
			$limit = $params['limit'];

		if(isset($params['por_linha']))
			$por_linha = $params['por_linha'];

		if(isset($params['quantidade_linha']))
			$quantidade_linha = $params['quantidade_linha'];

		if(isset($params['miniaturas']))
			$miniaturas = $params['miniaturas'];

		if(isset($params['first']))
			$first = $params['first'];

		if(isset($params['ordenar']))
			$ordenar = $params['ordenar'];

		if(isset($params['agrupar']))
			$agrupar = $params['agrupar'];
			
		if(isset($params['where']))
			$where = $params['where'];

		$link = $links['link'];
		$url	= $links['url'];
		if(isset($links['link_pag'])){
			$link_pag = $links['link_pag'];
		} else {
			$link_pag = $link;
		}

		$retorno = null;

		if(isset($where)){

			$this->db->where($where);

		}
		
		if(isset($limit)){

			$this->db->limit($limit);

		} elseif(isset($quantidade_linha)){

			$por_pagina = $quantidade_linha*$por_linha;

			$pagina_atual = $this->uri->segment($uri_segment,1);

				// Idioma
			$idioma = $this->session->userdata('idioma');
			if(!$idioma)
				$idioma = 1;

			if($this->config->item('gercont-multi-idioma') & $use_idioma)
				$this->db->where('idioma',$idioma);
			// Idioma
			if($use_ativo)
				$this->db->where($tabela.'.ativo',1);

			if(isset($params['where'])){
				foreach ($params['where'] as $key => $value) {
					$this->db->where($key,$value);
				}
			}
			
			if(isset($params['join'])){
				foreach ($params['join'] as $key => $value) {
					$this->db->join($key,$value);
				}
			}
			$total = $this->db->count_all_results($tabela);

			$this->load->library('pagination');
			$config['base_url'] = $link_pag . '/';
			$config['uri_segment'] = $uri_segment;
		  $config['total_rows'] = $total;
		  $config['per_page'] = $por_pagina;
		  $config['use_page_numbers'] = TRUE;
		  $this->pagination->initialize($config);

		  if($pagina_atual == 1){
				$incial = 0;
			} else {
				$incial = $por_pagina*($pagina_atual-1);
			}
			
			if( is_numeric($por_pagina) && is_numeric($incial) && intval($por_pagina) > 0 && intval($incial) > 0 ){
				$this->db->limit($por_pagina,$incial);
			}

		}

		// Idioma
		$idioma = $this->session->userdata('idioma');
		if(!$idioma)
			$idioma = 1;

		if($this->config->item('gercont-multi-idioma') & $use_idioma)
			$this->db->where('idioma',$idioma);
		// Idioma

		// Ordenar
		if(isset($ordenar))
			$this->db->order_by($ordenar[0],$ordenar[1]);

                if(isset($agrupar))
                    $this->db->group_by($agrupar);

		if($use_ativo)
			$this->db->where($tabela.'.ativo',1);

		if(isset($params['where'])){
			foreach ($params['where'] as $key => $value) {
				$this->db->where($key,$value);
			}
		}
		if(isset($params['join'])){
			foreach ($params['join'] as $key => $value) {
				$this->db->join($key,$value);
			}
		}
		$this->db->last_query();
		$this->db->select($tabela.'.*');

		$resultados = $this->db->get($tabela)->result_array();

		$total_resultados = count($resultados);

		$aux = 0;
		foreach ($resultados as $resultado) {

			$separador_elemento = null;
			// Verifica a quebra de linha
			$resultado['quebra_linha'] = null;
			if(isset($por_linha)){

				if($por_linha == $aux || $aux == 0){
					$resultado['quebra_linha'] = $quebra_linha;
					$aux = 0;

					if(isset($separador) && $key != $total_resultados && $key != 0){
						$separador_elemento = $separador;
					}

				}
			}

			// Converte a data para o formato brasileiro
			if(isset($resultado['data']))
				$resultado['data_br'] 			= data_br($resultado['data']);

			// Trás as 3 dimenções de imagens padrão
			if(isset($resultado['capa'])){
				$resultado['imagem'] 				= './uploads/' . $tabela . '/' . $resultado['id'] . '/' . $resultado['capa'];
				$resultado['imagem_micro'] 	= './uploads/' . $tabela . '/' . $resultado['id'] . '/micro_thumbs/' . $resultado['capa'];
				$resultado['imagem_mini']		= './uploads/' . $tabela . '/' . $resultado['id'] . '/mini_thumbs/' . $resultado['capa'];
			}

			// Trás mais dimenções de imagem passada por array
			if(isset($miniaturas)){
				foreach ($miniaturas as $miniatura) {
					$resultado['min_'.$miniatura]		= './uploads/' . $tabela . '/' . $resultado['id'] . '/' . $miniatura . '/' . $resultado['capa'];
				}
			}

			// Verifica a miniatura do vídeo
			if(isset($params['min_video'])){
				if($resultado['capa']){
					$resultado['thumb_video'] = $resultado['min_video'];
				} else {
					$video = $resultado[$params['min_video']];
					$thumb = miniatura_video(video_url($video));
					$resultado['thumb_video'] = $thumb['thumb'];
				}
			}

			if(isset($resultado[$url])){
				if( empty($link) || is_null($link) ) {
					$resultado['url'] 					= './' . $resultado[$url];
				} else {
					$resultado['url'] 					= './' . $link . '/' . $resultado[$url];
				}
			}

			if(isset($first) & $aux == 0)
				$resultado['first'] = $first;

			if(isset($resultado['arquivo']))
				$resultado['arquivo_download'] = './uploads/'. $tabela . '/' .$resultado['id']. '/'. $resultado['arquivo'];

			$retorno .= $separador_elemento;
			$retorno .= $this->parser->parse('templates/' . $tabela.'/_'.$layout, $resultado, TRUE);

			$aux++;

		}

		return $retorno;

	}

	/**
	*
	* fotos
	*
	* Faz a listagem de noticias
	*
	*
	* @access	public
	* @param	$tabela,
	* @return	mixed
	*/
	public function fotos($config){

		$ampliada = null;
		$miniatura = 'medium';

		if(isset($config['tabela']))
			$tabela = $config['tabela'];

		if(isset($config['campo']))
			$campo = $config['campo'];

		if(isset($config['layout']))
			$layout = $config['layout'];

		if(isset($config['registro']))
			$registro = $config['registro'];

		if(isset($config['img_ampliada']))
			$ampliada = $config['img_ampliada'];

		if(isset($config['img_miniatura']))
			$miniatura = $config['img_miniatura'];

		if(isset($config['por_linha']))
			$por_linha = $config['por_linha'];

		$retorno = null;

		$this->db->where('tipo','imagem');
		$this->db->where('tabela',$tabela);
		$this->db->where('registro',$registro);
		$this->db->where('campo',$campo);
		$resultados = $this->db->get('codemin_uploads')->result_array();

		$aux = 0;
		foreach ($resultados as $resultado) {

			$resultado['quebra_linha'] = null;
			if($por_linha){
				if($por_linha == $aux || $aux == 0){
					$resultado['quebra_linha'] = 'primeiro';
					$aux = 0;
				}
			}

			if(isset($resultado['arquivo'])){
				$resultado['miniatura']	= './uploads/imagem/' . $tabela . '/' . $registro . '/imagens/' . $miniatura . '/' . $resultado['arquivo'];
				$resultado['ampliada'] = './uploads/imagem/' . $tabela . '/' . $registro . '/imagens/' . $ampliada . '/' . $resultado['arquivo'];
				$retorno .= $this->parser->parse('templates/' . $tabela.'/_'.$layout, $resultado, TRUE);
			}


			$aux++;

		}

		return $retorno;

	}

	/**
	* voltar
	*
	* link para voltar a pagina anterior
	*
	*
	* @param string, string
	* @return string
	*/
	function voltar($class=null,$texto){

		return " <a href='javascript:history.back()' class='".$class."'>" .$texto. "</a> ";

	}



}
