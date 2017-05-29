
    <div class="container">

    	<div class="row">

    		<h1><?= $titulo ?></h1>

    		<div class="span6">

	        <div class="alert alert-success hide" id="sucesso_formulario"><?= $this->lang->line('newsletter_sucesso') ?></div>
	        <div class="alert alert-error hide" id="erro_formulario"></div>

	        <form action="./newsletter/enviar" class="form-horizontal" id="form_newsletter">

	            <div class="control-group">
	                <label class="control-label"><?= $this->lang->line('newsletter_nome') ?>*</label>
	                <div class="controls">
	                    <input type="text" name="nome" placeholder="<?= $this->lang->line('newsletter_nome') ?>">
	                </div>
	            </div>

	            <div class="control-group">
	                <label class="control-label"><?= $this->lang->line('newsletter_email') ?>*</label>
	                <div class="controls">
	                    <input type="text" name="email" placeholder="<?= $this->lang->line('newsletter_email') ?>">
	                </div>
	            </div>

	            <div class="control-group">
	                <div class="controls">
	                    <button type="submit" class="btn"><?= $this->lang->line('newsletter_enviar') ?></button>
	                </div>
	            </div>

	        </form>

		    </div>

    	</div>

    </div><!-- /.container -->