<h1>Novo pedido de <?= $cliente['nome'] ?></h1>
<hr>

<h2>Dados do Cliente:</h2>
<ul>
	<li><b>Nome:</b> <?= $cliente['nome'] ?></li>
	<li><b>Email:</b> <?= $cliente['email'] ?></li>
	<li><b>Telefone:</b> <?= $cliente['telefone'] ?></li>
	<li><b>Observações:</b> <?= $cliente['observacoes'] ?></li>
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
				<td><?= $produto['id'] ?></td>
				<td><?= $produto['nome'] ?></td>
				<td><?= $produto['price'] ?></td>
				<td><?= $produto['qty'] ?></td>
				<td><?= $produto['subtotal'] ?></td>
			</tr>
		<?php } ?>

		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><b>Total</b></td>
			<td><?= $this->cart->total() ?></td>
		</tr>

	</tbody>

</table>