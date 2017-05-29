<h1>Listar Contatos</h1>

<table id="registros" class="table table-striped table-bordered">

	<thead>
		<tr>
			<th>Nome</th>
			<th>Email</th>
			<th>Telefone</th>
			<th>Data</th>
			<th>Detalhes</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($contatos as $contato) { 
			if($contato->visualizado == 0){
				echo '<tr class="info">';
			} else {
				echo '<tr>';
			}
		?>
			<td><?= $contato->nome ?></td>
			<td><?= $contato->email ?></td>
			<td><?= $contato->telefone ?></td>
			<td><?= data_log($contato->data_hora) ?></td>
			<td><a href="./gercont/contatos/<?= $contato->id ?>" class="btn btn-success"><i class="icon-th icon-white"></i></a></td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<?= $this->pagination->create_links(); ?>