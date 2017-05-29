			
<div class="container marketing">   
<?php
	$localizacao = array(
		'tabela' => 'localizacao', // Pega resultados da tabela localizacao
		'layout' => 'item_home', // Com o layout em applicaiton/views/templates/downloda/_item_home.php
		'links' => array( // Link para rotas
			'url' => 'url_titulo',
			'link' => $this->lang->line('rota_localizcao')
		),
		'por_linha' => 999, // Listar dois resultados por linha 
											// (cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha)
		'quantidade_linha' => 1 // Exibir 2 linhas por pagina
	);
	echo '<ul id="getMaps">';
	echo $this->gercont->listagem($localizacao); 
	echo '<ul>';	
?>
	<?= $this->pagination->create_links(); ?>

	<div id="map_canvas" class="google-maps" style="width: 100%; height: 400px;"></div>

</div><!-- /.container -->