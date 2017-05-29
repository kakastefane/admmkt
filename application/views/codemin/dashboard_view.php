
<h1>Bem Vindo <strong><?= $this->session->userdata('nome') ?></strong></h1>

<hr>

<div class="row">

	<?php if(isset($novos_contatos) && count($novos_contatos) > 0){ ?>
	<div id="novos_contatos" class="col-sm-12 col-md-4">
		<div class="well">
			<h2>Novos Contatos</h2>
			<p>Você tem <?= count($novos_contatos) ?> novos <a href="./gercont/contatos/">contatos</a>.</p>
			<table class="table table-striped table-condensed">
					<thead>
						<tr>
							<th>Nome</th>
							<th>Data</th>
							<th>Detalhes</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($novos_contatos as $contato) { ?>
							<td><?= $contato->nome ?></td>
							<td><?= data_log($contato->data_hora) ?></td>
							<td><a href="./gercont/contatos/<?= $contato->id ?>">Visualizar</a></td>
						</tr>
					<?php } ?>
					</tbody>
			</table>
		</div>
	</div>
	<?php } ?>

	<?php if(isset($novos_newsletters) && count($novos_newsletters) > 0){ ?>
	<div id="novos_newsletters" class="col-sm-12 col-md-4">
		<div class="well">
			<h2>Novos Newsletters</h2>
			<p>Você tem <?= count($novos_newsletters) ?> novos cadastros no <a href="./gercont/newsletter/">newsletter</a>.</p>
			<table class="table table-striped table-condensed">
					<thead>
						<tr>
							<th>Nome</th>
							<th>Email</th>
							<th>Data</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($novos_newsletters as $newsletter) { ?>
							<td><?= $newsletter->nome ?></td>
							<td><?= $newsletter->email ?></td>
							<td><?= data_log($newsletter->data_hora) ?></td>
						</tr>
					<?php } ?>
					</tbody>
			</table>
		</div>
	</div>
	<?php } ?>

	<?php if(isset($novos_pedidos) && count($novos_pedidos) > 0){ ?>
	<div id="novos_pedidos" class="col-sm-12 col-md-4">
		<div class="well">
			<h2>Novos Pedidos</h2>
			<p>Você tem <?= count($novos_pedidos) ?> novos <a href="./gercont/catalogo_listar_pedidos/">pedidos</a>.</p>
			<table class="table table-striped table-condensed">
					<thead>
						<tr>
							<th>Nome</th>
							<th>Data</th>
							<th>Detalhes</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($novos_pedidos as $pedido) { ?>
							<td><?= $pedido->nome ?></td>
							<td><?= data_log($pedido->data_hora) ?></td>
							<td><a href="./gercont/catalogo_listar_pedidos/<?= $pedido->id ?>">Visualizar</a></td>
						</tr>
					<?php } ?>
					</tbody>
			</table>
		</div>
	</div>
	<?php } ?>

</div>
<!-- .row -->

<hr>

<div class="row">

	<?php if(isset($minhas_acoes)){ ?>
	<div id="log_acoes" class="col-sm-12 col-md-6">
		<div class="well">
			<h2>Minhas últimas ações</h2>
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Ação</th>
						<th>Registro</th>
						<th>Seção</th>
						<th>Data</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($minhas_acoes as $log) { ?>
					<tr>
						<td><?= texto_acao($log->acao) ?></td>
						<td>
							<?php if($log->acao == 3){ ?>
								<?= $log->id_registro ?>
							<?php } else { ?>
								<a href="<?= base_url() ?>gercont/<?= $log->tabela ?>/editar/<?= $log->id_registro ?>"><?= $log->id_registro ?></a>
							<?php } ?>
						</td>
						<td><a href="<?= base_url() ?>gercont/<?= $log->tabela ?>"><?= $log->tabela ?></a></td>
						<td><?= data_log($log->data); ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div><!-- #log_acoes -->
	<?php } ?>

	<?php if($meus_acessos){ ?>
	<div id="log_acessos" class="col-sm-12 col-md-6">
		<div class="well">
			<h2>Meus últimos acessos</h2>
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>IP Usuário</th>
						<th>Agente</th>
						<th>Data</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($meus_acessos as $log) { ?>
					<tr>
						<td><?= $log->ip_usuario ?></td>
						<td><?= $log->user_agent ?></td>
						<td><?= data_log($log->data); ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div><!-- #log_acessos -->
	<?php } ?>

	<?php if($log_acoes){ ?>
	<div id="log_acoes" class="col-sm-12 col-md-6">
		<div class="well">
			<h2>Últimas ações de todos os usuários</h2>
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Usuário</th>
						<th>Ação</th>
						<th>Registro</th>
						<th>Seção</th>
						<th>Data</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($log_acoes as $log) { ?>
					<tr>
						<td><a href="<?= base_url() ?>gercont/usuarios/editar/<?= $log->id ?>"><?= $log->nome ?></a></td>
						<td><?= texto_acao($log->acao) ?></td>
						<td>
							<?php if($log->acao == 3){ ?>
								<?= $log->id_registro ?>
							<?php } else { ?>
								<a href="<?= base_url() ?>gercont/<?= $log->tabela ?>/editar/<?= $log->id_registro ?>"><?= $log->id_registro ?></a>
							<?php } ?>
						</td>
						<td><a href="<?= base_url() ?>gercont/<?= $log->tabela ?>"><?= $log->tabela ?></a></td>
						<td><?= data_log($log->data); ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div><!-- #log_acoes -->
	<?php } ?>

	<?php if($log_acessos){ ?>
	<div id="log_acessos" class="col-sm-12 col-md-6">
		<div class="well">
			<h2>Últimos acessos de todos os usuários</h2>
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Usuário</th>
						<th>IP Usuário</th>
						<th>Agente</th>
						<th>Data</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($log_acessos as $log) { ?>
					<tr>
						<td><a href="<?= base_url() ?>gercont/usuarios/editar/<?= $log->id ?>"><?= $log->nome ?></a></td>
						<td><?= $log->ip_usuario ?></td>
						<td><?= $log->user_agent ?></td>
						<td><?= data_log($log->data); ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div><!-- #log_acessos -->
	<?php } ?>

</div>
<!-- .row -->