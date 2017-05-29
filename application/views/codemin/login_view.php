<div class="container">
<div class="row" style="margin-top: 150px;">

    <div class="col-sm-12 col-md-6">
    	<h1>Bem vindo ao <strong>Gercont</strong></h1>
    </div>

    <div class="col-sm-12 col-md-5 col-md-offset-1">
	    <div class="well">
		    <legend>Acessar o Gercont</legend>
		    <?= form_open('') ?>
		    	<?php if(isset($erro)){ ?>
			    <div class="alert alert-error">
			    	<a class="close" data-dismiss="alert" href="#">x</a>Login ou senha incorretos!
			    </div>
			    <?php } ?>

			    <div class="form-group">

			    	<div class="input-group">
						<div class="input-group-addon"><i class='fa fa-user'></i></div>
						<?php
					    if(!isset($login)){
					    	$login = null;
					    }
							$data = array(
								'name'        	=> 'login',
								'value'       	=> $login,
								'placeholder'	=> 'Login',
								'class'			=> 'form-control',
								'autofocus' => true
						    );
							echo form_input($data); ?>
			    	</div>

				</div>

				<div class="form-group">

			    	<div class="input-group">
						<div class="input-group-addon"><i class='fa fa-unlock'></i></div>
						<?php
						$data = array(
							'name'        	=> 'senha',
							'placeholder'	=> 'Senha',
							'class'			=> 'form-control'
					    );
						echo form_password($data); ?>
			    	</div>

				</div>

				<?= form_hidden('enviado',1); ?>

				<?php
				$data = array(
					'value'	=> 'Acessar',
					'class' => 'btn btn-success btn-lg'
			    );
				echo form_submit($data); ?>

		    <?= form_close() ?>
	    </div>
    </div>

</div>
</div>