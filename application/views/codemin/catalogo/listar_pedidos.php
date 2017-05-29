<h1>Listar Pedidos</h1>

<table id="registros" class="table table-striped table-bordered">

	<thead>
		<tr>
			<th>Cliente</th>
			<th>Email</th>
			<th>Telefone</th>
			<th>Observações</th>
			<th>Total</th>
			<th>Data</th>
			<th>Detalhes</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($pedidos as $pedido) { 
			if($pedido->visualizado == 0){
				echo '<tr class="info">';
			} else {
				echo '<tr>';
			}
		?>
			<td><?= $pedido->nome ?></td>
			<td><?= $pedido->email ?></td>
			<td><?= $pedido->telefone ?></td>
			<td><?= $pedido->observacoes ?></td>
			<td><?= moeda($pedido->total, 'br'); ?></td>
			<td><?= data_log($pedido->data_hora) ?></td>
			<td><a href="./gercont/catalogo_listar_pedidos/<?= $pedido->id ?>" class="btn btn-success"><i class="icon-th icon-white"></i></a></td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<?= $this->pagination->create_links(); ?>