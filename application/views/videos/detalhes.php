<div class="container">
	<h1><?= $detalhe->titulo ?></h1>
	<hr>

	<?php
		$video = miniatura_video(video_url($detalhe->video));
		echo '<iframe width="560" height="315" src="' . $video['embed'] . '" frameborder="0" allowfullscreen></iframe>';
	?>

	<?php var_dump($detalhe) ?>

</div>