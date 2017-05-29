			
<div class="container marketing">   
<?php
	$downloads = array(
		'tabela' => 'download', // Pega resultados da tabela noticias
		'layout' => 'item_home', // Com o layout em applicaiton/views/templates/downloda/_item_home.php
		'links' => array( // Link para rotas
			'url' => 'url_titulo',
			'link' => $this->lang->line('rota_download')
		),
		'por_linha' => 2, // Listar dois resultados por linha 
											// (cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha)
		'quantidade_linha' => 2 // Exibir 2 linhas por pagina
	);
echo $this->gercont->listagem($downloads); ?>

	<?= $this->pagination->create_links(); ?>

</div><!-- /.container -->