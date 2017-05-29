			
		<div class="container marketing">
    	<?php
			$noticias = array(
				'tabela' => 'noticias', // Pega resultados da tabela noticias
				'layout' => 'item_home', // Com o layout em applicaiton/views/templates/noticias/_item_home.php
				'links' => array( // Link para rotas
					'url' => 'url_titulo',
					'link' => $this->lang->line('rota_noticias')
				),
				'por_linha' => 1,
				'quantidade_linha' => 10,
				'miniaturas' => array('corte'),
				'ordenar' => array('data','desc'), // Ordenar os resultados
				'where' => array('data <=' => date('Y-m-d'))
			);
			$resultado = $this->gercont->listagem($noticias);
			if ($resultado)
				echo $resultado;
			else
				echo "<h3>Nenhuma notícia encontrado</h3>";
		?>

			<?php if ($resultado) echo $this->pagination->create_links(); ?>

		</div><!-- /.container -->