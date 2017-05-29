			
		<div class="container marketing">   
      <?php
      $noticias = array(
          'tabela' => 'galerias_fotos', // Pega resultados da tabela noticias
          'layout' => 'item_home', // Com o layout em applicaiton/views/templates/noticias/_item_home.php
          'links' => array( // Link para rotas
              'url' => 'url_nome',
              'link' => $this->lang->line('rota_galerias_fotos')
          ),
					'por_linha' => 2, // Listar dois resultados por linha 
														// (cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha)
					'quantidade_linha' => 2, // Exibir 2 linhas por pagina
          'ordenar' => array('ordenar','asc'), // Como deve ser ordenado
          'miniaturas' => array('corte')
      );
      echo $this->gercont->listagem($noticias); ?>

			<?= $this->pagination->create_links(); ?>

		</div><!-- /.container -->