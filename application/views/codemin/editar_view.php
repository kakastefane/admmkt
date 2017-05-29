<h1>Editar <?=$titulo?></h1>

<hr>

<div class="well">

	<?= validation_errors('<div class="alert alert-error">', '</div>'); ?>

	<?=$dados?>

</div>

<?php foreach ($contentBody as $content) {
    echo $content;
} ?>