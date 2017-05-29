<div class="container">
	<h1><?= $detalhe->titulo ?></h1><br/>
	<small><?= $detalhe->data ?></small>
	<hr>
	<figure class="img_fix_noticia"><img src="<?= './uploads/noticias/imagens/'.$detalhe->id.'/'.$detalhe->capa ?>" alt="<?= $detalhe->titulo ?>"></figure>
	<?= $detalhe->texto ?>

	<?php
	$config = array(
		'tabela' => 'noticias', // tabela
		'campo' => 'imagens', // campo da tabela
		'registro' => $detalhe->id, // Registro (id
		'layout' => 'galeria_fotos', // Com o template em applicaiton/views/templates/noticias/_item_home.php
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

	<?php 
	// Primerio => Nome da classe
	// Segundo => Texto
	echo $this->gercont->voltar('voltar', 'Voltar aee'); 
	?>

</div>