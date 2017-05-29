			
		<div class="container marketing">  

			<div class="row-fluid">

				<div class="span3 categorias">
					<h3>Categorias</h3>
					<?php
					$categorias = array(
						'tabela' => 'catalogo_categorias', // Pega resultados da tabela catalogo_categorias
						'layout' => 'item_home', // Com o layout em applicaiton/views/templates/catalogo_categorias/_item_home.php
						'links' => array( // Link para rotas
							'url' => 'url_nome',
							'link' => 'catalogo/categoria'
						),
						'idioma' => false
					);
		      echo $this->gercont->listagem($categorias); ?>
				</div>

		</div><!-- /.container -->