<div class="container">
	<h1></h1>
	<hr>

	<?php foreach($conteudos as $conteudo){ ?>

		<div>
		  <a href="<?= $conteudo->url_titulo ?>"><h2><?= $conteudo->titulo ?></h2></a>
		  <p><?= limitar_texto($conteudo->titulo,100) ?></p>
		</div>

		<hr/>

	<?php } ?>

</div>