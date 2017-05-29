			
		<div class="container marketing">  

			<div class="row-fluid">

				<div class="span3 categorias">
					<h3>Categorias</h3>
					<?php
					$categorias = array(
						'tabela' => 'codemin_opcoes', // Pega resultados da tabela codemin_opcoes
						'layout' => 'item_videos', // Com o layout em applicaiton/views/templates/codemin_opcoes/_item_videos.php
						'links' => array( // Link para rotas
							'url' => 'url_opcao',
							'link' => $this->lang->line('rota_galerias_videos') . '/categoria'
						),
						'where' => array(
							'codemin_opcoes.tabela' => 'videos',
							'codemin_opcoes.campo' => 'categoria'
						),
						'idioma' => false,
						'ativo' => false
					);
		      echo $this->gercont->listagem($categorias); ?>
				</div>

				<div class="span9 listagem">
		    	<?php
					$videos = array(
						'tabela' => 'videos', // Pega resultados da tabela catalogo_produtos
						'layout' => 'item_home', // Com o layout em applicaiton/views/templates/catalogo_produtos/_item_home.php
						'links' => array( // Link para rotas
							'url' => 'url_titulo',
							'link' => $this->lang->line('rota_galerias_videos')
						),
						'miniaturas' => array('video'),
						'min_video' => 'video',
						'por_linha' => 1, // Listar dois resultados por linha 
															// (cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha)
						'quantidade_linha' => 1 // Exibir 1 linhas por pagina
					);
					if(isset($categoria)){
						$videos['where'] = array('codemin_opcoes.url_opcao' => $categoria);
						$videos['join'] = array(
							'codemin_opcoes' => 'codemin_opcoes.id = videos.categoria'
						);
						$videos['uri_segment'] = 4;
						$videos['links'] = array(
							'url' => 'url_titulo',
							'link' => $this->lang->line('rota_galerias_videos'),
							'link_pag' => $this->lang->line('rota_galerias_videos') . '/categoria/' . $categoria
						);
					}
		      $resultado = $this->gercont->listagem($videos);
		      echo $resultado; ?>

					<?php if($resultado) echo $this->pagination->create_links(); ?>
				</div> 

		</div><!-- /.container -->