<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "site/index";
$route['404_override'] = 'site/conteudo';

// Rotas do Codemin
$route['gercont'] 				= "codemin/administrador";
$route['gercont/(:any)']	= "codemin/administrador/$1";
$route['instalar'] 							= "codemin/inicial";
$route['opcoes'] 								= "codemin/opcoes";
$route['opcoes/(:any)'] 				= "codemin/opcoes/$1";
$route['uploader'] 							= "codemin/uploader";
$route['uploader/(:any)'] 			= "codemin/uploader/$1";

// Tradução de rotas
/* Caso necessite altear as rotas, deve ser feito em todos os idiomas 
e também em ./application/language/{idioma}/gercont_lang.php na seção de rotas*/

	// ⁽portugues|english|spanol|italiano|deutsch)
	$noticias 				= '^(noticias|news|noticias|notizie|nachrichten)';
	$contato 					= '^(contato|contact|contacto|contatto|kontakt)';
	$galerias_fotos 	= '^(galerias-fotos|photo-galleries|galerias-fotos|gallerie-fotografiche|foto-galerien)';
	$galerias_videos 	= '^(galerias-videos|videos-galleries|galerias-videos|video-gallerie|videos-galerien)';
	$conteudos 				= '^(conteudo|content|contenido|contenuto|inhalt)';

	// Rotas do Gercont 2
	/* ========== noticias ========== */
	$route[$noticias] 					= "noticias/todas";
	$route[$noticias . '/:num'] = "noticias/todas";
	$route[$noticias . '/:any']	= "noticias/detalhes";

	/* ========== galerias_fotos ========== */
	$route[$galerias_fotos] 					= "galerias_fotos/todas";
	$route[$galerias_fotos . '/:num'] = "galerias_fotos/todas";
	$route[$galerias_fotos . '/:any'] = "galerias_fotos/detalhes";

	/* ========== galerias_videos ========== */
	$route[$galerias_videos] 													= "videos/todos";
	$route[$galerias_videos . '/:num'] 								= "videos/todos";
	$route[$galerias_videos . '/categoria/:any'] 			= "videos/categorias";
	$route[$galerias_videos . '/categoria/:any/:num'] = "videos/categorias";
	$route[$galerias_videos . '/:any'] 								= "videos/detalhes";

	/* ========== catalogo ========== */
	$route['catalogo/carrinho'] 								= "catalogo/carrinho";
	$route['catalogo/carrinho/adicionar/:num'] 	= "catalogo/adicionar";
	$route['catalogo/carrinho/remover/:any'] 		= "catalogo/remover";
	$route['catalogo/carrinho/limpar'] 					= "catalogo/limpar";
	$route['catalogo/carrinho/atualizar'] 			= "catalogo/atualizar";
	$route['catalogo/carrinho/finalizar'] 			= "catalogo/finalizar";
	$route['catalogo/carrinho/finalizado'] 			= "catalogo/finalizado";
	$route['catalogo/carrinho/retorno/:any'] 			= "catalogo/retorno";

	$route['catalogo'] 											= "catalogo/todas_categorias";
	$route['catalogo/:num'] 								= "catalogo/todas_categorias";
	$route['catalogo/categoria/:any'] 			= "catalogo/todos_produtos";
	$route['catalogo/categoria/:any/:num'] 	= "catalogo/todos_produtos";
	$route['catalogo/:any/:any']									= "catalogo/detalhes";

	/* ========== conteudos ========== */
	$route[$conteudos . '/:any']	= "site/conteudos";

	/* ========== contato ========== */
	$route[$contato] 							= "site/contato";
	$route[$contato . '/enviar'] 	= "site/enviar_contato";

	/* ========== newsletter ========== */
	$route['newsletter'] = "newsletter/form";
	$route['newsletter/enviar'] = "newsletter/enviar";

	$route['sitemap.xml'] = 'site/sitemap';
	$route['robots.txt'] = 'site/robotstxt';
	


/* End of file routes.php */
/* Location: ./application/config/routes.php */