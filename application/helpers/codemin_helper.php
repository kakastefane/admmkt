<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| HELPER CODEMIN
| -------------------------------------------------------------------------
| Desenvolvido por Bruno Almeida
|
*/

function senha_usuario($senha){
	$hash = "codemin_hash";
	return md5($senha.$hash);
}

/**
* verificar_video
* 
* Verifica qual é o vídeo (youtube ou vimeo) e retorna um array com o link e o tipo
* 
*
* @param	$string
* @return	array
*/
function verificar_video($url){
	$return = array();
	if(is_youtube($url)){
		$return['tipo'] = 'youtube';
		$return['link'] = youtube_video_id($url);
	} elseif(is_vimeo($url)){
		$return['tipo'] = 'vimeo';
		$return['link'] = vimeo_video_id($url);
	}
	return $return;
}

/**
* is_youtube
* 
* Verifica se a url é do youtube
* 
*
* @param	$string
* @return	boolean
*/
function is_youtube($url){
	return (preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url));
}

/**
* youtube_video_id
* 
* Pega a url do youtube e retorna o ID do video
* 
*
* @param	$string
* @return	$string
*/
function youtube_video_id($url){
	if(is_youtube($url)){
		$pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
		preg_match($pattern, $url, $matches);
		if (count($matches) && strlen($matches[7]) == 11){
			return $matches[7];
		}
	}
	return '';
}

/**
* is_vimeo
* 
* Verifica se a url é do vimeo
* 
*
* @param	$string
* @return	boolean
*/
function is_vimeo($url){
	return (preg_match('/vimeo\.com/i', $url));
}

/**
* vimeo_video_id
* 
* Pega a url do vimeo e retorna o ID do video
* 
*
* @param	$string
* @return	$string
*/
function vimeo_video_id($url){
	if(is_vimeo($url)){
		$pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
		preg_match($pattern, $url, $matches);
		if (count($matches)){
			return $matches[2];
		}
	}
	return '';
}

/**
* video_url
* 
* Pega o ID do youtube ou vimeo e retorna a url video
* 
*
* @param	$string
* @return	$string
*/
function video_url($url){
	if(strlen($url) == 11){
		return youtube_video_url($url);
	} else {
		return vimeo_video_url($url);
	}
}

/**
* youtube_video_url
* 
* Pega o ID do youtube e retorna a url video
* 
*
* @param	$string
* @return	$string
*/
function youtube_video_url($url){
	return "http://www.youtube.com/watch?v=".$url;
}

/**
* vimeo_video_url
* 
* Pega o ID do vimeo e retorna a url video
* 
*
* @param	$string
* @return	$string
*/
function vimeo_video_url($url){
	return "http://vimeo.com/".$url;
}

/**
* miniatura video
* 
* Pega o link do video e retorna a miniatura e o embed
* 
*
* @param	$string
* @return	array
*/
function miniatura_video($link){
	$result = array();
	if (is_youtube($link)) {
		$video_id = youtube_video_id($link);
		$embedurl = 'http://www.youtube.com/embed/'.$video_id;
		$thumbnail_str = 'http://img.youtube.com/vi/'.$video_id.'/1.jpg';
	} elseif (is_vimeo($link)) {
	    $video_id = vimeo_video_id($link);
		$embedurl = 'http://player.vimeo.com/video/'.$video_id;
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$video_id.".php"));
		if (is_array($hash)) {
			$thumbnail_str = $hash[0]['thumbnail_small'];
		}
	}

	$result['embed'] = $embedurl;
	$result['thumb'] = $thumbnail_str;
	return $result;
}


/**
* video_iframe_url
* 
* Gera a URL pro iframe do vídeo (embed)
* 
*
* @param	$string
* @return	$string
*/
function video_iframe_url($url){
	if(strlen($url) == 11){
		return youtube_embed_url($url);
	} else {
		return vimeo_embed_url($url);
	}
}

/**
* vimeo_embed_url
* 
* Retorna a url do iframe pro embed do vimeo
* 
*
* @param	$string
* @return	$string
*/
function vimeo_embed_url($url){
	return "//player.vimeo.com/video/" . $url;
}

/**
* youtube_embed_url
* 
* Retorna a url do iframe pro embed do youtube
* 
*
* @param	$string
* @return	$string
*/
function youtube_embed_url($url){
	return "//www.youtube.com/embed/" . $url;
}

/**
* texto_acao
* 
* Pega o ID do vimeo e retorna a url video
* 
*
* @param	$string
* @return	$string
*/
function texto_acao($id){
	switch ($id) {
		case 1:
			return "Adicionou";
			break;

		case 2:
			return "Editou";
			break;

		case 3:
			return "Removeu";
			break;
	}
}

/**
* data_log
* 
* Retorna a data corretamente
* 
*
* @param	$string
* @return	$string
*/
function data_log($data){

	$aux = explode(" ", $data);
	$data = $aux[0];
	$hora = $aux[1];

	if($data == date('Y-m-d')){
		$data = "Hoje";
	} elseif ($data == date('Y-m-d', strtotime('- 1 day') )) {
		$data = "Ontem";
	} else {
		$data = implode('/',array_reverse(explode('-',$data)));
	}

	return $data . ' às ' . $hora;

}


/**
* url_amigavel
* 
* Retira acentos, substitui espaço por - e
* deixa tudo minúsculo
* 
*
* @param	$string
* @return	$string
*/
function url_amigavel($variavel){
		$procurar 	= array('à','ã','â','é','ê','í','ó','ô','õ','ú','ü','ç',);
		$substituir = array('a','a','a','e','e','i','o','o','o','u','u','c',);
		$variavel = strtolower($variavel);
		$variavel	= str_replace($procurar, $substituir, $variavel);
		$variavel = htmlentities($variavel);
    $variavel = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $variavel);
    $variavel = preg_replace("/([^a-z0-9]+)/", "-", html_entity_decode($variavel));
    return trim($variavel, "-");
}


/**
* array2csv
* 
* Transforma um array para formato csv
* 
*
* @param	array
* @return	file
*/
function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}


/**
* download_send_headers
* 
* Seta os cabeçalhos para download de arquivo
* 
*
* @param	array
* @return	file
*/
function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

