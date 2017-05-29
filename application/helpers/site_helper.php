<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/*
| -------------------------------------------------------------------------
| Site Helper
| -------------------------------------------------------------------------
| Desenvolvido por Bruno Almeida
|
*/
 
 
 
/**
* data_br
*
* Converte uma data no formato mysql para o formato brasileiro
*
*
* @param string
* @return string
*/
function data_br($data_bd){
	return implode('/',array_reverse(explode('-',$data_bd)));
}
 
function letrasRandomicas($len = 5){

  $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  $base = strlen($charset);
  $result = '';

  $now = explode(' ', microtime());
  $now = $now[1];

  while ($now >= $base){
    $i = $now % $base;
    $result = $charset[$i] . $result;
    $now /= $base;
  }
  return substr($result, -5);

}

function captcha()
{
	
	$CI =& get_instance();

	$CI->load->helper('captcha');
	
	if( !is_dir('./captcha') ){
		@mkdir('./captcha');
	}

	$vals = array(
	    'word' => letrasRandomicas(10), 
	    'img_path' => './captcha/', 
	    'img_url' => base_url() . 'captcha/', 
	    'font_path' => '', 
	    'img_width' => '200', 
	    'img_height' => '30', 
	    'expiration' => 7200
    );

	$cap = create_captcha($vals);

	$CI->session->set_userdata('captcha',$cap['word']);

	return $cap;

}
 
function RD_Station($dados,$salesforce=0)
{

	$retorno = array();

	$url_rd         = 'https://www.rdstation.com.br/api/1.3/conversions';
	$url_sales      = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
	$oid 			= 'oid SalesForce'; // oid na Sales Force
	$token 			= 'token-rd';
	$token_privado 	= 'token-privado-rd';

	$dados['token_rdstation'] = $token;

	$url_rd = str_replace('TOKEN',$token,$url_rd);

	$data = $dados;
	$data_string = json_encode($data);

	$ch = curl_init($url_rd);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json',
	    'Content-Length: ' . strlen($data_string))
	);

	$retorno['RD'] = curl_exec($ch);

	curl_close($ch);

	//Sales Force
	
	if($salesforce == 1){
		
		$data = $_POST;

		$dados_permitidos 		= array('nome','sobrenome','txtSobrenome','email','telefone','txtFoneCelular','empresa','txtRazaoSocial','estado','mensagem', 'txtCidadeFatu');
		$dados_correspondentes 	= array(
									'nome' => 'first_name', 
									'sobrenome' => 'last_name', 
									'txtSobrenome' => 'last_name', 
									'telefone' => 'phone',
									'txtFoneCelular' => 'phone',
									'empresa' => 'company',
									'txtRazaoSocial' => 'company', 
									'estado' => 'state_code', 
									'mensagem' => 'description',
									'txtCidadeFatu' => 'city'
									);

		foreach( $data AS $campo => $valor ){

			if( in_array($campo,$dados_permitidos) ){

				if( array_key_exists($campo, $dados_correspondentes) ){
					$campo_novo 		= $dados_correspondentes[$campo];
					$data[$campo_novo]	= $valor;
					unset($data[$campo]);
				}

			} else {

				unset($data[$campo]);

			}

		}

		$data['oid'] 			= $oid;
		$data['retURL'] 		= 'http://www.dedalus.com.br/retorno';
		$data['country_code'] 	= 'BR';
		$data['lead_source'] 	= 'WEB Site Dedalus';

		$data['encoding'] 	= 'UTF-8';


		//$data['debug'] = 1;
		//$data['debugEmail'] = "luiz.lopes@wingsit.com.br";

		$data = http_build_query($data);

		$ch = curl_init($url_sales);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION,CURLOPT_HTTP_VERSION_1_1);

		$retorno['Sales'] = curl_exec($ch);

		$retorno['Sales_fields'] = $data;

		curl_close($ch);

	}
	
	return $retorno;

}
 
/**
* data_bd
*
* Converte uma data no formato brasileiro para o formato mysql
*
*
* @param string
* @return string
*/
function data_bd($data_br){
	return implode('-',array_reverse(explode('/',$data_br)));
}
 
 
 
/**
* limitar_texto
*
* Remove todas as tags HTML e limita os caractéres do texto, adicionando ... se for maior que o limite
*
*
* @param string, int
* @return string
*/
function limitar_texto($texto,$limit){
	$texto = strip_tags($texto);
	if(strlen($texto) > $limit){
		return substr($texto,0,$limit).'...';
	} else {	
		return substr($texto,0,$limit);
	}
}
 
/**
* array_idiomas
*
* Define idiomas do gercont
*
*
* @param null
* @return array
*/
function array_idiomas($namespace = false){

	// Não excluir, apenas comentar

	if($namespace){

		$idiomas = array();
		$idiomas[1] = 'portugues-br';
		$idiomas[2] = 'english';
		$idiomas[3] = 'espanol'; 
		// $idiomas[4] = 'italiano';
		// $idiomas[5] = 'deutsch';

	} else {

		$idiomas = array();
		$idiomas[1] = 'Português';
		$idiomas[2] = 'English';
		$idiomas[3] = 'Español';
		// $idiomas[4] = 'Italiano';
		// $idiomas[5] = 'Deutsch';

	}

	return $idiomas;
}

/**
* array_idiomas_select
*
* Define idiomas para o select
*
*
* @param null
* @return array
*/
function array_idiomas_select(){

	$idiomas_select = array();
	$idiomas = array_idiomas();
	$idiomas_select[0] = 'Todos';
	foreach ($idiomas as $key => $idioma) {
		$idiomas_select[$key] = $idioma;
	}

	$resultado = array();
	$resultado[0] = 'Filtro - Idioma';
	$resultado['Idioma'] = $idiomas_select;

	return $resultado;
}
 
/**
* rotas_do_sistema
*
* Trás as rotas do sistema liberadas
*
*
* @param null
* @return array
*/
function rotas_do_sistema($id = null){

	$rotas = array();

	if($id){

		$rotas[1]	= "rota_catalogo";
		$rotas[2]	= 'rota_contato';
		$rotas[3]	= 'rota_conteudo';
		$rotas[4]	= 'rota_galerias_fotos';
		$rotas[5]	= 'rota_galerias_videos';
		$rotas[6]	= "rota_newsletter";
		$rotas[7]	= 'rota_noticias';

		return $rotas[$id];

	} else {

		$CI =& get_instance();

		if($CI->config->item('gercont-gerenciar-catalogo'))
			$rotas[1]	= "Catálogo";

		if($CI->config->item('gercont-gerenciar-contato'))
			$rotas[2]	= "Contato";

		if($CI->config->item('gercont-gerenciar-conteudo'))
			$rotas[3]	= "Conteúdo";

		if($CI->config->item('gercont-gerenciar-fotos'))
			$rotas[4]	= "Galerias de Fotos";

		if($CI->config->item('gercont-gerenciar-videos'))
			$rotas[5]	= "Galerias de Videos";

		if($CI->config->item('gercont-gerenciar-newsletter'))
			$rotas[6]	= "Newsletter";

		if($CI->config->item('gercont-gerenciar-noticias'))
			$rotas[7]	= "Notícias";

		return $rotas;

	}
}

 
 
/**
* nome_idioma
*
* Recebe o id e retorna o nome do idioma
*
*
* @param int
* @return string
*/
function nome_idioma($id){

	$idiomas = array_idiomas();

	if(isset($idiomas[$id]))
		return $idiomas[$id];

	return 'Não Definido';
}

 
 
/**
* namespace_idioma
*
* Recebe o id e retorna o namespace do idioma
*
*
* @param int
* @return string
*/
function namespace_idioma($id){

	$idiomas = array_idiomas(true);
	
	if(isset($idiomas[$id]))
		return $idiomas[$id];

	return 'Não Definido';
}

 
 
/**
* id_idioma
*
* Recebe o namespace e retorna o id do idioma
*
*
* @param string
* @return id
*/
function id_idioma($namespace){

	$idiomas = array_flip(array_idiomas(true));
	
	if(isset($idiomas[$namespace]))
		return $idiomas[$namespace];

	return 1;
}
 
 
 
/**
* enviar_email
*
* Faz o envio de e-mail
*
*
* @param string, string, string/array
* @return boolean
*/
function enviar_email($destinatarios,$assunto,$corpo,$responder=null){
 
	$CI =& get_instance();
	 
	$config = array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://smtp.gmail.com',
		'smtp_port' => 465,
		'smtp_user' => 'testesmtp@webi.com.br',
		'smtp_pass' => 'smtp3241',
		'mailtype'	=> 'html'
	);
	 
	$CI->load->library('email', $config);
	$CI->email->set_newline("\r\n");
	 
	$CI->email->from('testesmtp@webi.com.br', 'Agencia WEBI');
	$CI->email->subject($assunto);
	$CI->email->message($corpo);
	$CI->email->bcc('formulario@webi.com.br'); 

	if($responder){
		$CI->email->reply_to($responder);
	}
	 
	$CI->email->to($destinatarios);
	 
	return $CI->email->send();
	
}

/**
* moeda
*
* Recebe a moeda e retorna no formato
*
*
* @param string, string
* @return string
*/
function moeda($valor, $formato){

	switch ($formato) {
		case 'en':
			$result = number_format($valor, 2, '.', ',');
			break;
		case 'br':
			$result = number_format($valor, 2, ',', '.');
			break;
		default:
			$result = 'Nada';
			break;
	}

	return $result;
}

