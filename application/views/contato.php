
    <div class="container">

    	<div class="row">

    		<h1><?= $contato->titulo ?></h1>

    		<div class="span6">

	        <div class="alert alert-success hide" id="sucesso_formulario"><?= $this->lang->line('contato_sucesso') ?></div>
	        <div class="alert alert-error hide" id="erro_formulario"></div>

	        <form action="./<?= $this->lang->line('rota_contato') ?>/enviar" class="form-horizontal" id="form_contato">

	            <div class="control-group">
	                <label class="control-label"><?= $this->lang->line('contato_nome') ?>*</label>
	                <div class="controls">
	                    <input type="text" name="nome" class="required" placeholder="<?= $this->lang->line('contato_nome') ?>">
	                </div>
	            </div>

	            <div class="control-group">
	                <label class="control-label"><?= $this->lang->line('contato_email') ?>*</label>
	                <div class="controls">
	                    <input type="text" name="email" class="required email" placeholder="<?= $this->lang->line('contato_email') ?>">
	                </div>
	            </div>

	            <div class="control-group">
	                <label class="control-label"><?= $this->lang->line('contato_telefone') ?></label>
	                <div class="controls">
	                    <input type="text" name="telefone" placeholder="(49) 9999-99999">
	                </div>
	            </div>

	            <div class="control-group">
	                <label class="control-label"><?= $this->lang->line('contato_mensagem') ?>*</label>
	                <div class="controls">
	                    <textarea name="mensagem" class="required" placeholder="<?= $this->lang->line('contato_mensagem') ?>"></textarea>
	                </div>
	            </div>

	            <div class="control-group">
	                <div class="controls">
	                    <button type="submit" class="btn"><?= $this->lang->line('contato_enviar') ?></button>
	                </div>
	            </div>

	        </form>

		    </div>

		    <div class="span6">
		        <p style="text-align: center;"><?= nl2br($contato->texto) ?></p>
		        <br/>
		        <div id="google-maps" style="width: 100%; height: 300px;"></div>
		        <br/>
		        <p style="text-align: center;"><?= nl2br($contato->endereco) ?></p>
		    </div>

    	</div>

    </div><!-- /.container -->