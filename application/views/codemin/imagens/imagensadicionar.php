<div class="bootstrap" id="content">
    <div class="bootstrap">
        <div class="page-head">
            <h2 class="page-title">Adicionar novo</h2>
        </div>
    </div>        


    <form method="post" action="./gercont/imagens/adicionar" class="defaultForm form-horizontal">
        <div id="fieldset_0" class="panel">
            <div class="panel-heading"><i class="icon-picture"></i>Tipo de Imagem</div>

            <div class="form-wrapper">

                <div class="control-group">
                    <label class="control-label col-lg-3 required">
                        <span title="" data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="													Apenas letras, underscores (travessão-à-linha) e hífens (sinal «-»). Exemplos:  &amp;quot;pequena_personalizada&amp;quot;, &amp;quot;carrinho_media&amp;quot;, &amp;quot;grande&amp;quot;, &amp;quot;caixa-tamanho_xxl&amp;quot;.">
                            Titulo:
                        </span>
                    </label>

                    <div class="col-lg-9 controls">
                        <input type="text" required="required" class="" value="" id="name" name="name">
                    </div>
                </div>


                <div class="control-group">

                    <label class="control-label col-lg-3 required">
                        <span title="" data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="A largura máxima de imagens em pixels.">
                            Largura:
                        </span>
                    </label>

                    <div class="col-lg-9 controls">
                        <div class="input-group">
                            <input type="text" required="required" maxlength="5" class="" value="" id="width" name="width">
                            <span class="input-group-addon">pixels</span>
                        </div>
                    </div>

                </div>


                <div class="control-group">

                    <label class="control-label col-lg-3 required">
                        <span title="" data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="A altura máxima das imagens em pixels.">
                            Altura:
                        </span>
                    </label>

                    <div class="col-lg-9 controls">
                        <div class="input-group">
                            <input type="text" required="required" maxlength="5" class="" value="" id="height" name="height">
                            <span class="input-group-addon">pixels</span>

                        </div>
                    </div>

                </div>


                <?php foreach ($configuracoes as $key => $value) { ?>
                    <div class="control-group">
                        <label class="control-label"><?= strtoupper(substr($value, 7)); ?>:</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox" value="<?= $key ?>" name="json_pages[]">
                                Sim
                            </label>
                        </div>
                    </div>
                <?php } ?>

            </div>

        </div>

        <div class="col-lg-4">
            <button class="btn btn-default" id="image_type_form_submit_btn" value="1" type="submit">
                <i class="process-icon-save"></i> Salvar
            </button>
            <a onclick="window.history.back();" class="btn btn-default" href="#">
                <i class="process-icon-cancel"></i> Cancelar
            </a>
        </div>

    </form>
</div>