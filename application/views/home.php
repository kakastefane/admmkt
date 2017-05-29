    <div class="container">

        <div id="myCarousel" class="carousel slide">
            <!-- Carousel items -->
            <div class="carousel-inner">
                <?php
                $banners = array(
                    'tabela' => 'banners', // Pega resultados da tabela banners
                    'layout' => 'item_home', // Com o layout em applicaiton/views/templates/banners/_item_home.php
                    'links' => array( // Link para rotas
                        'url' => 'url_nome',
                        'link' => $this->lang->line('rota_banners')
                    ),
                    'limit' => 5, // Lista apenas os 4 ultimos registros
                    'miniaturas' => array('banner'),
                    'first' => 'active', // Adiciona somente no primeiro elemento
                    'ordenar' => array('ordenar','asc') // Ordenar os resultados
                );
                echo $this->gercont->listagem($banners); ?>
            </div> 
            <!-- Carousel nav -->
            <a class="carousel-control left" href="#myCarousel" data-slide="prev">‹</a>
            <a class="carousel-control right" href="#myCarousel" data-slide="next">›</a>
        </div>

        <hr>

        <div class="noticias">
            <ul>
            <?php
            $noticias = array(
                'tabela' => 'noticias', // Pega resultados da tabela noticias
                'layout' => 'item_home', // Com o layout em applicaiton/views/templates/noticias/_item_home.php
                'links' => array( // Link para rotas
                    'url' => 'url_titulo',
                    'link' => $this->lang->line('rota_noticias')
                ),
                'limit' => 4, // Lista apenas os 4 ultimos registros
                'miniaturas' => array('corte'),
                'ordenar' => array('data','desc') // Ordenar os resultados
            );
            echo $this->gercont->listagem($noticias); ?>
            </ul>
        </div>  

    </div><!-- /.container -->