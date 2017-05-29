<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title><?php
            if (isset($titulo)) {
                $titulo_site = $titulo . ' - ' . $configuracoes->titulo;
            } else {
                $titulo_site = $configuracoes->titulo;
            }
            echo $titulo_site;
            ?></title>
        <base href="<?= base_url() ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="canonical" href="<?= current_url() ?>" />
        <link rel="shortcut icon" href="./public/images/favicon.ico" />
        <?php if (isset($configuracoes->publicador_link)) echo '<link rel="publisher" href="' . $configuracoes->publicador_link . '"/>'; ?>
        <?php if (isset($pgdescription)) echo '<meta name="description" content="' . $pgdescription . '"/>'; ?>
        <?php if (isset($pgkeywords)) echo '<meta name="keywords" content="' . $pgkeywords . '"/>'; ?>

        <!-- FACEBOOK -->
        <meta property="og:site_name" content="<?= $configuracoes->titulo ?>"/>
        <meta property="og:url" content="<?= current_url() ?>"/>
        <meta property="og:title" content="<?= $titulo_site ?>"/>
        <meta property="og:type" content="website"/>
        <?php if (isset($configuracoes->publicador)) echo '<meta property="article:publisher" content="' . $configuracoes->publicador . '" />'; ?>
        <?php if (isset($fbimg)) echo '<meta property="og:image" content="' . $fbimg . '"/>'; ?>

        <!-- CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./public/fancybox/source/jquery.fancybox.css?v=2.1.5" />
        <link rel="stylesheet" href="./public/css/normalize.css">
        <link rel="stylesheet" href="./public/css/estilo.css">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://modernizr.com/downloads/modernizr-latest.js"></script>
        <![endif]-->

        <?php if (isset($configuracoes->analytics)) { ?>

            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

                ga('create', '<?= $configuracoes->analytics ?>', 'auto');
                ga('send', 'pageview');

            </script>
        <?php } ?>

    </head>

    <body>

        <!-- NAVBAR
        ================================================== -->

        <div class="container">

            <?php if (!$this->uri->segment(1)) { ?>
                <h1><a class="logo" href="<?= base_url(); ?>" title="<?= $titulo_site ?>"><?= $titulo_site ?></a></h1>
            <?php } else { ?>
                <p><a class="logo" href="<?= base_url(); ?>" title="<?= $titulo_site ?>"><?= $titulo_site ?></a></p>
            <?php } ?>

            <div class="navbar menu">
                <?= $configuracoes->menu ?>
            </div>

            <hr>

            <?php if ($configuracoes->link_facebook != "" || $configuracoes->link_twitter != "" || $configuracoes->link_googleplus != "" || $configuracoes->link_instagram != "" || $configuracoes->link_linkedin != "" || $configuracoes->link_youtube != "") { ?>
                <h3>Conecte-se</h3>

                <ul class="socialLinks">
                    <?php if ($configuracoes->link_facebook != "") { ?><li><a href="<?= $configuracoes->link_facebook ?>" title="Facebook"><i class="fa fa-facebook"></i></a></li><?php } ?>
                    <?php if ($configuracoes->link_twitter != "") { ?><li><a href="<?= $configuracoes->link_twitter ?>" title="Twitter"><i class="fa fa-twitter"></i></a></a></li><?php } ?>
                    <?php if ($configuracoes->link_googleplus != "") { ?><li><a href="<?= $configuracoes->link_googleplus ?>" title="Google Plus"><i class="fa fa-google-plus"></i></a></a></li><?php } ?>
                    <?php if ($configuracoes->link_instagram != "") { ?><li><a href="<?= $configuracoes->link_instagram ?>" title="Instagram"><i class="fa fa-instagram"></i></a></a></li><?php } ?>
                    <?php if ($configuracoes->link_linkedin != "") { ?><li><a href="<?= $configuracoes->link_linkedin ?>" title="LinkedIn"><i class="fa fa-linkedin"></i></a></a></li><?php } ?>
                    <?php if ($configuracoes->link_youtube != "") { ?><li><a href="<?= $configuracoes->link_youtube ?>" title="Youtube"><i class="fa fa-youtube"></i></a></a></li><?php } ?>
                </ul>
            <?php } ?>

            <?php if ($configuracoes->texto_header != "" || $configuracoes->num_telefone != "" || $configuracoes->num_whatsapp != "" || $configuracoes->end_email != "") { ?>
                <h3>Contatos</h3>

                <ul class="contatos listNone">
                    <?php if ($configuracoes->texto_header != "") { ?><li><?= $configuracoes->texto_header ?></li><?php } ?>
                    <?php if ($configuracoes->num_telefone != "") { ?><li><i class="fa fa-phone"></i> <?= $configuracoes->num_telefone ?></li><?php } ?>
                    <?php if ($configuracoes->num_whatsapp != "") { ?><li><i class="fa fa-whatsapp"></i> <?= $configuracoes->num_whatsapp ?></li><?php } ?>
                    <?php if ($configuracoes->end_email != "") { ?><li><i class="fa fa-envelope-o"></i> <?= $configuracoes->end_email ?></li><?php } ?>
                </ul>
            <?php } ?>

            <hr>

            <div>
                <b><a href="./catalogo/carrinho">Carrinho</a></b><br/>
                <?= $this->cart->total_items() ?> Itens e R$ <?= $this->cart->total() ?> - <a href="./catalogo/carrinho/limpar">Limpar</a>
            </div>

            <?php
            if ($this->session->flashdata('sucesso'))
                echo "<div class='alert alert-success'>" . $this->session->flashdata('sucesso') . "</div>";

            if ($this->session->flashdata('erro'))
                echo "<div class='alert alert-error'>" . $this->session->flashdata('erro') . "</div>";
            ?>

        </div>