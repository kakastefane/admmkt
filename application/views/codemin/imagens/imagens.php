<div class="bootstrap" id="content">
    <div class="bootstrap">
        <div class="page-head">
            <h2 class="page-title">Imagens</h2>
        </div>
    </div>        



    <form id="form-image_type" class="form-horizontal clearfix" action="index.php?controller=AdminImages&amp;token=1182251689f022d17e18b08e1ac23981#image_type" method="post">


        <input type="hidden" value="0" name="submitFilterimage_type" id="submitFilterimage_type">

        <div class="panel col-lg-12">
            <div class="panel-heading">
                <span class="">
                    <a href="./gercont/imagens/adicionar" class="list-toolbar-btn">
                        <span dtitle="Adicionar novo" class="label-tooltip" data-toggle="tooltip" title="">
                            Adicionar
                        </span>
                    </a>
                </span>
            </div>


            <div class="table-responsive clearfix">
                <table class="table image_type">
                    <thead>
                        <tr class="nodrag nodrop">
                            <th class="fixed-width-xs center">
                                <span class="title_box active">Código</span>
                            </th>
                            <th class="">
                                <span class="title_box">Nome</span>
                            </th>
                            <th class="">
                                <span class="title_box">Largura</span>
                            </th>
                            <th class="">
                                <span class="title_box">Altura</span>
                            </th>
                            <?php foreach ($configuracoes as $value) { ?>
                                <th class=" center">
                                    <span class="title_box"><?= strtoupper(substr($value, 7)); ?></span>
                                </th>
                            <?php } ?>

                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($dados) {
                            foreach ($dados as $dado) {
                                ?>
                                <tr class="odd">
                                    <td class="pointer fixed-width-xs center">
                                        <?= $dado->id ?>
                                    </td>

                                    <td class="pointer" id='name-<?= $dado->id ?>'>
                                        <?= $dado->nome ?>
                                    </td>

                                    <td class="pointer">
                                        <?= $dado->largura ?>
                                        px																			
                                    </td>

                                    <td class="pointer">
                                        <?= $dado->altura ?>
                                        px																			
                                    </td>

                                    <?php
                                    foreach ($configuracoes as $key => $value) {
                                        if (!in_array($key, json_decode($dado->json_pages))) {
                                            ?>
                                            <td class="pointer center">
                                                <span class="list-action-enable action-disabled">
                                                    <i class="icon-remove"></i>
                                                </span>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td class="pointer center">
                                                <span class="list-action-enable action-enabled">
                                                    <i class="icon-check"></i>
                                                </span>
                                            </td>
                                            <?php
                                        }
                                    }
                                    ?>


                                    <td class="text-right">
                                        <div class="btn-group-action">				
                                            <div class="btn-group pull-right">
                                                <a class="edit btn btn-default" title="Alterar" href="./gercont/imagens/editar/<?= $dado->id ?>">
                                                    <i class="icon-pencil"></i> Alterar
                                                </a>
                                                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                                    <i class="caret"></i>&nbsp;
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="delete" title="Eliminar" onclick="if (confirm('Deletar o item selecionado?\nNome ' + $('#name-'+<?= $dado->id ?>).text())) {
                                                                            return true;
                                                                        } else {
                                                                            event.stopPropagation();
                                                                            event.preventDefault();
                                                                        }
                                                                        ;" href="./gercont/imagens/excluir/<?= $dado->id ?>">
                                                            <i class="icon-trash"></i> Eliminar
                                                        </a>							
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>					
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>