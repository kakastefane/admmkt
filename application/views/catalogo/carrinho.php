<div class="container">
	<h1>Meu Carrinho</h1>
	<hr>

	<?php if(count($produtos) > 0){ ?>

	<form action="./catalogo/carrinho/finalizar" method="post" id="form-catalogo">

		<input id="form-finalizar" type="hidden" value="./catalogo/carrinho/finalizar">
		<input id="form-atualizar" type="hidden" value="./catalogo/carrinho/atualizar">

		<table class="table">

			<thead>
				<tr>
					<th>Código</th>
					<th>Imagem</th>
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
						<td><img src="<?= $produto['img'] ?>"></td>
						<td><?= $produto['nome'] ?></td>
						<td class="valor"><?= moeda($produto['price'], 'br'); ?></td>
						<td><div class="input-append input-prepend">
								<button class="rem btn">-</button>
								<input type="text" name="<?= $produto['rowid'] ?>" value="<?= $produto['qty'] ?>" class="span1" readonly>
								<button class="add btn">+</button>
								<a href="./catalogo/carrinho/remover/<?= $produto['rowid'] ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i></a>
							</div>
						</td>
						<td class="subtotal"><?= moeda($produto['subtotal'], 'br'); ?></td>
					</tr>
				<?php } ?>

				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><b>Total</b></td>
					<td class="total"><?= moeda($this->cart->total(), 'br'); ?></td>
				</tr>

			</tbody>

		</table>

		<input type="submit" value="Atualizar e Finalizar" class="submit">
		<input type="submit" value="Atualizar" class="atualizar">

	</form>

	<?php } else { ?>

	<h2>Seu carrinho não contém nenhum produto!</h2>

	<?php } ?>

</div>