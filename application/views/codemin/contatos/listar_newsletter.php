<h1>Listar Newsletter</h1>

<a href="./gercont/newsletter/exportar/todos" class="btn btn-success" target="_blank">Exportar Todos</a>
<a href="./gercont/newsletter/exportar/ultimos" class="btn btn-success" target="_blank">Exportar Últimos</a>

<table id="registros" class="table table-striped table-bordered">

	<thead>
		<tr>
			<th>Nome</th>
			<th>Email</th>
			<th>Data</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($contatos as $contato) { 
			if($contato->exportado == 0){
				echo '<tr class="info">';
			} else {
				echo '<tr>';
			}
		?>
			<td><?= $contato->nome ?></td>
			<td><?= $contato->email ?></td>
			<td><?= data_log($contato->data_hora) ?></td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<?= $this->pagination->create_links(); ?>