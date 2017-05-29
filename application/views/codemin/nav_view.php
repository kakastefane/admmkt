<nav class="navbar navbar-default">
	<div class="container-fluid">

		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?=base_url()?>gercont">GERCONT</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="divider-vertical"></li>
				<?php foreach ($navlinks as $key => $value) { ?>
				<li <?php if($activelink == $key){ echo 'class="active"'; } ?>><a href="<?=base_url()?>gercont/<?=$key?>"><i class="fa fa-edit"></i> <?=$value?></a></li>
				<?php } ?>
				<?php if(($this->session->userdata('administrador') || in_array('catalogo_categorias', $permissoes) || in_array('catalogo_produtos', $permissoes) || in_array('catalogo_configuracao', $permissoes) || in_array('catalogo_listar_pedidos', $permissoes)) && $this->config->item('gercont-gerenciar-catalogo')){ ?>
				<li <?php if($activelink == 'catalogo_categorias' ||  $activelink == 'catalogo_produtos' ||  $activelink == 'catalogo_configuracao' || $activelink == 'catalogo_listar_pedidos'){ echo 'class="active"'; } ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-edit"></i> Catálogo</a>
					<ul class="dropdown-menu">
						<?php if($this->session->userdata('administrador') || in_array('catalogo_categorias', $permissoes)){ ?>
						<li><a href="<?=base_url()?>gercont/catalogo_categorias">Categorias</a></li>
						<?php } if($this->session->userdata('administrador') || in_array('catalogo_produtos', $permissoes)){ ?>
						<li><a href="<?=base_url()?>gercont/catalogo_produtos">Produtos</a></li>
						<?php } if($this->session->userdata('administrador') || in_array('catalogo_configuracao', $permissoes)){ ?>
						<li><a href="<?=base_url()?>gercont/catalogo_configuracao">Configuração</a></li>
						<?php } if($this->session->userdata('administrador') || in_array('catalogo_listar_pedidos', $permissoes)){ ?>
						<li><a href="<?=base_url()?>gercont/catalogo_listar_pedidos">Listar Pedidos</a></li>
						<?php } ?>
					</ul>
				</li>
			  	<?php } ?>
			  	<?php if(($this->session->userdata('administrador') || in_array('contato', $permissoes) || in_array('contatos', $permissoes) || in_array('newsletter', $permissoes)) && $this->config->item('gercont-gerenciar-contato')){ ?>
				<li <?php if($activelink == 'contato' ||  $activelink == 'contatos' || $activelink == 'newsletter'){ echo 'class="active"'; } ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-edit"></i> Contatos</a>
					<ul class="dropdown-menu">
						<?php if($this->session->userdata('administrador') || in_array('contatos', $permissoes)){ ?>
						<li><a href="<?=base_url()?>gercont/contatos">Recebidos</a></li>
						<?php } if($this->session->userdata('administrador') || in_array('contato', $permissoes)){ ?>
						<li><a href="<?=base_url()?>gercont/contato">Configurar</a></li>
						<?php } if($this->session->userdata('administrador') || in_array('newsletter', $permissoes)){ ?>
						<li><a href="<?=base_url()?>gercont/newsletter">Newsletter</a></li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li <?php if(!$activelink){ echo 'class="active"'; } ?>><a href="<?=base_url()?>gercont"><i class="fa fa-home"></i></a></li>
				<li><a target="_blank" href="<?=base_url()?>"><i class="fa fa-globe"></i></a></li>
				<li <?php if($activelink == 'usuarios'){ echo 'class="active"'; } ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></a>
					<ul class="dropdown-menu">
						<?php if($this->session->userdata('administrador')){ ?>
						<li><a href="<?=base_url()?>gercont/usuarios"><i class="fa fa-user"></i> Usuários</a></li>
						<?php } ?>
						<li><a href="<?=base_url()?>gercont/logout"><i class="fa fa-sign-out"></i> Sair do Sitema</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<!-- .navbar-collapse -->
	</div>
	<!-- .container-fluid -->
</nav>

<div style="width: 98%; margin-left: 1%;">

	<?php
	if($this->session->flashdata('sucesso')){
		echo "<p class='alert alert-success'>" . $this->session->flashdata('sucesso') . "<button type='button' class='close' data-dismiss='alert'>&times;</button><p>";
	}
	if($this->session->flashdata('erro')){
		echo "<p class='alert alert-error'>" . $this->session->flashdata('erro') . "<button type='button' class='close' data-dismiss='alert'>&times;</button><p>";
	}
?>