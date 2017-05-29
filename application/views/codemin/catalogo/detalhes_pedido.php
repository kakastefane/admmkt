<h1>Pedido de <?= $cliente->nome ?></h1>
<hr>

<h2>Dados do Cliente:</h2>
<ul>
	<li><b>Nome:</b> <?= $cliente->nome ?></li>
	<li><b>Email:</b> <?= $cliente->email ?></li>
	<li><b>Telefone:</b> <?= $cliente->telefone ?></li>
	<li><b>Observações:</b> <?= $cliente->observacoes ?></li>
	<li><b>Data:</b> <?= date('d/m/Y - H:i:s') ?></li>
</ul>

<hr>

<h2>Dados do Pedido</h2>

<table class="table">

	<thead>
		<tr>
			<th>Código</th>
			<th>Nome</th>
			<th>Valor</th>
			<th>Quantidade</th>
			<th>Subtotal</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($produtos as $produto) { ?>
			<tr>
				<td><?= $produto->id ?></td>
				<td><?= $produto->nome ?></td>
				<td><?= moeda($produto->preco, 'br'); ?></td>
				<td><?= $produto->quantidade ?></td>
				<td><?= moeda($produto->subtotal, 'br'); ?></td>
			</tr>
		<?php } ?>

		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><b>Total</b></td>
			<td><?= moeda($cliente->total,'br'); ?></td>
		</tr>

	</tbody>

</table>

<a href="./gercont/catalogo_listar_pedidos" class="btn">Voltar</a>