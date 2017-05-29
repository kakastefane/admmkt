<div class="container">
	<h1><?= $detalhe->nome ?></h1>
	<hr>

	<p><a href="./catalogo/carrinho/adicionar/<?= $detalhe->id ?>">Adicionar ao Carrinho</a></p>

	<?php var_dump($detalhe) ?>

	<?php
	$config = array(
		'tabela' => 'catalogo_produtos', // tabela
		'campo' => 'imagens', // campo da tabela
		'registro' => $detalhe->id, // Registro (id
		'layout' => 'galeria_fotos', // Com o template em applicaiton/views/templates/catalogo_produtos/_galeria_fotos.php
		'img_ampliada' => null, // Imagem ampliada, null ou não definir para mostrar a original
		'img_miniatura' => 'medium', // Imagem do thumb, null ou não definir para mostrar a medium
		'por_linha' => 6 	// Listar dois resultados por linha 
										 	// (cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha)
	);
  $fotos = $this->gercont->fotos($config); // Guarda o retorno das fotos ?>

  <?php if($fotos){ // Se tiver fotos mostra o título e as fotos ?>
	  <h2>Fotos</h2>
		<ul>
			<?= $fotos ?>
	  </ul>
  <?php } ?>



  <!-- PRODUTOS RELACIONADOS COM A CATEGORIA -->

  <?php
		$produtos = array(
			'tabela' => 'catalogo_produtos', // Pega resultados da tabela catalogo_produtos
			'layout' => 'item_home', // Com o layout em applicaiton/views/templates/catalogo_produtos/_item_home.php
			'links' => array( // Link para rotas
				'url' => 'url_nome',
				'link' => 'catalogo'
			),
			'por_linha' => 3, // Listar dois resultados por linha 
												// (cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha)
			'quantidade_linha' => 3, // Exibir 2 linhas por pagina
			'idioma' => false,
			'limit' => 3,
			'ordenar' => array('rand()')
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

</div>