<style>
	table thead tr th {
		height: 30px;
		line-height: 30px!important;
	}
	table thead tr th input.input-busca {
		display: none;
	}
	table thead tr th button.btn-cancelar {
		margin-top: 2px;
		margin-left: 5px;
	}
</style>

<?php if($nivel_acesso == 3){ ?>
	<p id="ordenar-sucesso" class="alert alert-success hide">Ordenação gravada com sucesso</p>
	<p id="ordenar-erro" class="alert alert-error hide">Houve um erro ao gravar a ordenação ou você não tem permissão para isso</p>
	<p id="ordenar-carregando" class="alert alert-info hide">Gravando ordenação, aguarde</p>
<?php } ?>

<h1>Listar <?=$titulo?></h1>

<hr/>

<a class="btn btn-success" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/adicionar"?>">Adicionar</a>

<div class="filtro_codemin">
	<select name="status" class="form-control">
		<option>Filtro - Status</option>
		<optgroup label="Status">
			<option value="">Todos</option>
			<option value="ativo">Ativo</option>
			<option value="inativo">Inativo</option>
		</optgroup>
	</select>
	<?php
	if($this->config->item('gercont-multi-idioma')){
		echo form_dropdown('idioma', array_idiomas_select());
	}
	?>
</div>

<div style="clear: both;"></div>

<br>

<?php $areas_sem_idioma = array('catalogo_categorias','catalogo_produtos','usuarios'); ?>

<table id="registros" class="table table-striped table-bordered <?php if(isset($config["ordenar_drag"])) echo 'drag_ordenar'; ?>">

	<?php if($nivel_acesso == 3){
		if(isset($config['ordenar_drag'])){ ?>
	<caption class="text-info">Clique e arraste para ordenar os registros</caption>
	<?php } } else { ?>
	<caption class="text-info">Você tem permissão para visualizar e editar somente os seus registros</caption>
	<?php } ?>

	<thead>
		<tr>
			<th>Id</th>
			<?php foreach($listagens as $listagem){ ?>
			<th>
				<?=str_replace('_',' ',ucfirst($listagem))?>
				<button class="btn btn-info btn-sm pull-right btn-busca"><i class="fa fa-search fa fa-white"></i></button>
				<button class="btn btn-info btn-sm pull-right btn-cancelar" style="display: none;"><i class="fa fa-times fa fa-white"></i></button>
				<input type="text" id="busca-<?= $listagem ?>" placeholder="Buscar por <?=ucfirst($listagem)?>" style="width: 30%;" class="pull-right form-control input-sm search-query input-busca" />
			</th>
			<?php } ?>
			<?php if($this->config->item('gercont-multi-idioma') && !in_array($this->uri->segment(2),$areas_sem_idioma)){ ?>
				<th>Idioma</th>
			<?php } ?>
			<th>Criado por</th>
			<th>Status</th>
			<th>Ações</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($resultados as $resultado) { ?>
		<tr id="registro_<?=$resultado['id']?>">
			<td>
				<a href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/editar/".$resultado['id']?>"><?=$resultado['id']?></a>
				<?php
				if(isset($resultado['capa'])){
					if($resultado['capa'] != ''){
						echo " <img src='".base_url()."uploads/".$this->uri->segment(2)."/".$resultado['id']."/micro_thumbs/".$resultado['capa']."' class='img-polaroid' width='50' /> ";
					}
				} ?>
			</td>
			<?php foreach($listagens as $listagem){ ?>
			<!--<td><?=$resultado[$listagem]?></td>-->
			<td><!-- Quando for um menu filho acrescenta traços antes do nome do menu -->
				<?php if(isset($resultado['menu_pai']) && $resultado['menu_pai'] >= 1){
					echo ' -- '.$resultado[$listagem];
				}else{
					echo $resultado[$listagem];
				}
				?>
			</td>
			<?php } ?>
			<?php if($this->config->item('gercont-multi-idioma') && !in_array($this->uri->segment(2),$areas_sem_idioma)){ ?>
			<td class="idioma"><?php if(isset($resultado['idioma'])){
				echo nome_idioma($resultado['idioma']);
			} else {
				echo "Não Definido";
			} ?>
			<input type="hidden" value="<?= $resultado['idioma'] ?>"></td>
			<?php } ?>
			<td><?php
				if(isset($resultado['nome_usuario'])){
					echo $resultado['nome_usuario'];
				} else {
					echo "Sistema";
				}
			?></td>
			<td class="ativo">
				<?php if($resultado['ativo']){ ?>
					 	<span class="label label-success" data-id="<?= $resultado['id'] ?>" data-tabela="<?= $this->uri->segment(2) ?>">Ativo</span>
				<?php } else { ?>
					 	<span class="label label-inverse" data-id="<?= $resultado['id'] ?>" data-tabela="<?= $this->uri->segment(2) ?>">Inativo</span>
				<?php } ?>
			</td>
			<td>
				<div class="btn-group">
					<a class="btn btn-primary" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/editar/".$resultado['id']?>"><i class="fa fa-edit fa fa-white"></i></a>
					<a class="btn btn-danger confirmar-excluir" data-id="<?= $resultado['id'] ?>" data-tabela="<?= $this->uri->segment(2) ?>" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/excluir/".$resultado['id']?>"><i class="fa fa-remove fa fa-white"></i></a>
				</div>
				<!-- <button class='btn btn-info'><i class="fa fa-move fa fa-white"></i></button> -->
			</td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<a class="btn btn-success" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/adicionar"?>">Adicionar</a>