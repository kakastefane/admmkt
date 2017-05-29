			
		<div class="container marketing">  

			<div class="row-fluid">

				<div class="span9 listagem">
		    	<?php
					$produtos = array(
						'tabela' => 'catalogo_produtos', // Pega resultados da tabela catalogo_produtos
						'layout' => 'item_home', // Com o layout em applicaiton/views/templates/catalogo_produtos/_item_home.php
						'links' => array( // Link para rotas
							'url' => 'url_nome',
							'link' => 'catalogo'
						),
						'por_linha' => 1, // Listar dois resultados por linha 
															// (cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha)
						'quantidade_linha' => 1, // Exibir 2 linhas por pagina
						'idioma' => false
					);

					if(isset($categoria)){

						$produtos['where'] = array('catalogo_categorias.url_nome' => $categoria);
						$produtos['join'] = array(
							'codemin_opcoes_selecionadas' => 'catalogo_produtos.id = codemin_opcoes_selecionadas.id_registro',
							'catalogo_categorias' => 'catalogo_categorias.id = codemin_opcoes_selecionadas.id_opcao'
						);
						$produtos['uri_segment'] = 4;
						$produtos['links'] = array(
							'url' => 'url_nome',
							'link' => 'catalogo/' . $categoria,
							'link_pag' => 'catalogo/categoria/' . $categoria
						);
					}
		      $resultado = $this->gercont->listagem($produtos);
		      echo $resultado; ?>

					<?php if($resultado) echo $this->pagination->create_links(); ?>
				</div> 

		</div><!-- /.container -->