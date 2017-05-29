<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * DESENVOLVIDO POR BRUNO ALMEIDA
 *
 * MAIO DE 2013
 */

class Administrador extends CI_Controller {

    public function conteudo() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Título da Página
        $dados[] = array(
            'titulo' => 'Título da Página',
            'campo' => 'titulo',
            'url_amigavel' => true,
            'tipo' => 'input-text',
            'placeholder' => 'Agência WEBI'
        );

        $dados[] = array(
            'titulo' => 'Meta keywords',
            'campo' => 'keywords',
            'tipo' => 'input-text',
            'placeholder' => 'palavra chave 1, palavra chave 2, pala...'
        );

        $dados[] = array(
            'titulo' => 'Meta Description',
            'campo' => 'description',
            'tipo' => 'text-area',
            'placeholder' => 'Descrição para resultados nos buscadores',
            'dica' => 'Utilize poucas palavras, serão apresentadas nos resultados dos buscadores'
        );

        // Texto do corpo
        $dados[] = array(
            'titulo' => 'Texto',
            'campo' => 'texto',
            'tipo' => 'text-area-rich',
            'placeholder' => 'Texto completo',
            'dica' => 'Texto completo com imagens, iframes e tudo o que sua criatividade e o html permitirem'
        );
        // Imagens (galeria de imagens)
        $dados[] = array(
            'titulo' => 'Imagens da Galeria',
            'campo' => 'imagens',
            'tipo' => 'imagens',
            'dica' => 'Clique em Gerenciar Imagens para selecionar imagens para upload'
        );

        $config = array(
            'listagem' => array('titulo', 'url_titulo'), // O que vai aparecer na listagem
            'titulo' => "Conteúdo"
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function menu() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Título da Página
        $dados[] = array(
            'titulo' => 'Título',
            'campo' => 'titulo',
            'tipo' => 'input-text',
            'url_amigavel' => true,
            'placeholder' => 'A Empresa'
        );
        // Pai
        $this->load->model('gercont_model');
        $array = $this->gercont_model->menu_select();
        $dados[] = array(
            'titulo' => 'Menu Pai',
            'campo' => 'menu_pai',
            'tipo' => 'select',
            'dados' => $array
        );


        // Função
        $array = array(0 => 'Link Manual', 1 => 'Link de Conteúdo', 2 => 'Link do Gercont');
        $dados[] = array(
            'titulo' => 'Função',
            'campo' => 'funcao',
            'tipo' => 'radio',
            'dados' => $array
        );
        // Link Manual
        $dados[] = array(
            'titulo' => 'Link Manual',
            'campo' => 'link_manual',
            'tipo' => 'input-text',
            'placeholder' => './a-empresa',
            'dica' => 'A base da url é ' . base_url()
        );
        // Link de Conteúdo
        $dados[] = array(
            'titulo' => 'Link de Conteúdo',
            'campo' => 'link_conteudo',
            'tipo' => 'checkbox',
            'dados' => $this->gercont_model->conteudos_select()
        );
        // Link do Gercont
        $dados[] = array(
            'titulo' => 'Link do Gercont',
            'campo' => 'link_gercont',
            'tipo' => 'radio',
            'dados' => rotas_do_sistema()
        );
        // Abrir link
        $array = array(0 => 'Mesma Aba', 1 => 'Nova Aba');
        $dados[] = array(
            'titulo' => 'Abrir',
            'campo' => 'abrir',
            'tipo' => 'radio',
            'dados' => $array
        );

        $config = array(
            'listagem' => array('titulo'), // O que vai aparecer na listagem
            'titulo' => "Menu",
            'ordenar_drag' => true // não dá a opção de ordernar por arrastar
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function noticias() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Título
        $dados[] = array(
            'titulo' => 'Título',
            'campo' => 'titulo',
            'url_amigavel' => true,
            'tipo' => 'input-text',
            'dica' => 'O Título deve conter no máximo 250 caractéres',
            'placeholder' => 'Título da Galeria'
        );

        $dados[] = array(
            'titulo' => 'Meta keywords',
            'campo' => 'keywords',
            'tipo' => 'input-text',
            'placeholder' => 'palavra chave 1, palavra chave 2, pala...'
        );

        $dados[] = array(
            'titulo' => 'Meta Description',
            'campo' => 'description',
            'tipo' => 'text-area',
            'placeholder' => 'Descrição para resultados nos buscadores',
            'dica' => 'Utilize poucas palavras, serão apresentadas nos resultados dos buscadores'
        );

        // Data
        $dados[] = array(
            'titulo' => 'Data de Publicação',
            'campo' => 'data',
            'tipo' => 'data',
            'placeholder' => '19/07/2013',
            'dica' => 'Os exemplos serão ordenados no site pela data',
        );
        // Texto da chamada
        $dados[] = array(
            'titulo' => 'Chamada',
            'campo' => 'chamada',
            'tipo' => 'text-area',
            'placeholder' => 'Texto para a chamada',
            'dica' => 'O texto deve ser curto para chamar o visitante'
        );
        // Texto do corpo
        $dados[] = array(
            'titulo' => 'Texto',
            'campo' => 'texto',
            'tipo' => 'text-area-rich',
            'placeholder' => 'Texto completo',
            'dica' => 'Texto completo com imagens, iframes e tudo o que sua criatividade e o html permitirem'
        );
        // Imagens (galeria de imagens)
        $dados[] = array(
            'titulo' => 'Imagens da Notícia',
            'campo' => 'imagens',
            'tipo' => 'imagens',
            'dica' => 'Clique em Gerenciar Imagens para selecionar imagens para upload'
        );
        // Videos (galeria de videos)
        $dados[] = array(
            'titulo' => 'Vídeos da Notícia',
            'campo' => 'videos',
            'tipo' => 'videos'
        );
        // Imagem
        $miniaturas[] = array('corte', 350, 250, true); // criar versão de exatos 350x250px
        $dados[] = array(
            'titulo' => 'Capa',
            'campo' => 'capa',
            'tipo' => 'imagem',
            'miniaturas' => $miniaturas,
            'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif'
        );

        $config = array(
            'listagem' => array('titulo', 'data'), // O que vai aparecer na listagem
            'titulo' => "Noticias",
            'order_by' => array('noticias.data' => 'DESC')
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function videos() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Título
        $dados[] = array(
            'titulo' => 'Título',
            'campo' => 'titulo',
            'url_amigavel' => true,
            'tipo' => 'input-text',
            'dica' => 'O Título deve conter no máximo 250 caractéres',
            'placeholder' => 'Título do Vídeo'
        );
        // Url
        $dados[] = array(
            'titulo' => 'Url do Vídeo',
            'campo' => 'video',
            'tipo' => 'video',
            'dica' => 'A Url pode ser do vimeo ou youtube',
            'placeholder' => 'http://www.youtube.com/watch?v=8sgycukafqQ'
        );
        // Categoria
        $dados[] = array(
            'titulo' => 'Categoria',
            'campo' => 'categoria',
            'tipo' => 'select-dinamico'
        );
        // Descrição
        $dados[] = array(
            'titulo' => 'Descrição',
            'campo' => 'descricao',
            'tipo' => 'text-area',
            'placeholder' => 'Descrição do Vídeo'
        );
        // Imagem
        $miniaturas[] = array('video', 120, 90, true); // criar versão de exatos 350x250px
        $dados[] = array(
            'titulo' => 'Thumb do Video',
            'campo' => 'capa',
            'tipo' => 'imagem',
            'miniaturas' => $miniaturas,
            'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif (deixe em branco para pegar do servidor)'
        );

        $config = array(
            'listagem' => array('titulo'), // O que vai aparecer na listagem
            'titulo' => "Vídeos",
            'order_by' => array('videos.titulo' => 'ASC')
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function galerias_fotos() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Título
        $dados[] = array(
            'titulo' => 'Nome da Galeria',
            'campo' => 'nome',
            'url_amigavel' => true,
            'tipo' => 'input-text',
            'dica' => 'O Título deve conter no máximo 250 caractéres',
            'placeholder' => 'Título da Galeria'
        );

        $dados[] = array(
            'titulo' => 'Meta keywords',
            'campo' => 'keywords',
            'tipo' => 'input-text',
            'placeholder' => 'palavra chave 1, palavra chave 2, pala...'
        );

        $dados[] = array(
            'titulo' => 'Meta Description',
            'campo' => 'description',
            'tipo' => 'text-area',
            'placeholder' => 'Descrição para resultados nos buscadores',
            'dica' => 'Utilize poucas palavras, serão apresentadas nos resultados dos buscadores'
        );
        // Texto da chamada
        $dados[] = array(
            'titulo' => 'Chamada',
            'campo' => 'chamada',
            'tipo' => 'text-area',
            'placeholder' => 'Texto para a chamada',
            'dica' => 'O texto deve ser curto para chamar o visitante'
        );
        // Texto do corpo
        $dados[] = array(
            'titulo' => 'Texto',
            'campo' => 'texto',
            'tipo' => 'text-area-rich',
            'placeholder' => 'Texto completo',
            'dica' => 'Texto completo com imagens, iframes e tudo o que sua criatividade e o html permitirem'
        );
        // Imagem
        $miniaturas[] = array('corte', 350, 250, true); // criar versão de exatos 350x250px
        $dados[] = array(
            'titulo' => 'Capa',
            'campo' => 'capa',
            'tipo' => 'imagem',
            'miniaturas' => $miniaturas,
            'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif'
        );
        // Imagens (galeria de imagens)
        $dados[] = array(
            'titulo' => 'Imagens da Galeria',
            'campo' => 'imagens',
            'tipo' => 'imagens',
            'dica' => 'Clique em Gerenciar Imagens para selecionar imagens para upload'
        );

        $config = array(
            'listagem' => array('nome', 'chamada'), // O que vai aparecer na listagem
            'titulo' => "Galerias de Fotos"
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function banners() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Título
        $dados[] = array(
            'titulo' => 'Nome do Banner',
            'campo' => 'nome',
            'url_amigavel' => true,
            'tipo' => 'input-text',
            'dica' => 'O Título deve conter no máximo 250 caractéres',
            'placeholder' => 'Nome do Banner para organização'
        );
        // Texto da chamada
        $dados[] = array(
            'titulo' => 'Link',
            'campo' => 'link',
            'tipo' => 'input-text',
            'placeholder' => 'http://www.google.com.br/',
            'dica' => 'A URL do banner deve ser absoluta com o http:// ou https://'
        );
        // Data
        $dados[] = array(
            'titulo' => 'Data Inicial',
            'campo' => 'data_inicial',
            'tipo' => 'data',
            'placeholder' => '19/07/2013',
            'dica' => 'Primeiro dia em que o banner estará visível',
        );
        // Data
        $dados[] = array(
            'titulo' => 'Data Final',
            'campo' => 'data_final',
            'tipo' => 'data',
            'placeholder' => '19/07/2013',
            'dica' => 'Último dia em que o banner estará visível',
        );
        // Imagem
        $miniaturas[] = array('banner', 940, 350, true); // criar versão de exatos 350x250px
        $dados[] = array(
            'titulo' => 'Capa',
            'campo' => 'capa',
            'tipo' => 'imagem',
            'miniaturas' => $miniaturas,
            'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif em 350x250px'
        );

        $config = array(
            'listagem' => array('nome', 'data_inicial', 'data_final', 'link'), // O que vai aparecer na listagem
            'titulo' => "Banners",
            'ordenar_drag' => true // não dá a opção de ordernar por arrastar
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function download() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Título
        $dados[] = array(
            'titulo' => 'Título',
            'campo' => 'titulo',
            'url_amigavel' => true,
            'tipo' => 'input-text',
            'dica' => 'O Título deve conter no máximo 250 caractéres',
            'placeholder' => 'Título da Galeria'
        );
        // Texto
        $dados[] = array(
            'titulo' => 'Texto',
            'campo' => 'texto',
            'tipo' => 'text-area',
            'placeholder' => 'Texto completo'
        );
        // Arquivo
        $dados[] = array(
            'titulo' => 'Arquivo',
            'campo' => 'arquivo',
            'tipo' => 'arquivo'
        );


        $config = array(
            'listagem' => array('titulo', 'arquivo'), // O que vai aparecer na listagem
            'titulo' => "Downlodas"
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function localizacao() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        // Maps
        $dados[] = array(
            'titulo' => 'Maps',
            'campo' => 'maps',
            'tipo' => 'google-maps'
        );
        // Titulo MAPS
        $dados[] = array(
            'titulo' => 'Titulo',
            'campo' => 'titulo',
            'tipo' => 'hidden'
        );
        // Endreço MAPS
        $dados[] = array(
            'titulo' => 'Endereco',
            'campo' => 'endereco',
            'tipo' => 'hidden'
        );
        // CEP MAPS
        $dados[] = array(
            'titulo' => 'CEP',
            'campo' => 'cep',
            'tipo' => 'input-text'
        );
        // Telefone
        $dados[] = array(
            'titulo' => 'Telefone',
            'campo' => 'telefone',
            'tipo' => 'input-text',
            'placeholder' => 'Telefone'
        );


        $config = array(
            'listagem' => array('titulo'), // O que vai aparecer na listagem
            'titulo' => "Localização"
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function configuracoes() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }

        // Título do site
        $dados[] = array(
            'titulo' => 'Título do site',
            'campo' => 'titulo',
            'tipo' => 'input-text',
            'placeholder' => 'Agência WEBI',
            'dica' => 'Título estára disponível no title do site'
        );

        $dados[] = array(
            'titulo' => 'Facebook',
            'campo' => 'link_facebook',
            'tipo' => 'input-text',
            'placeholder' => 'Link para o seu Facebook',
            'dica' => 'Exemplo: http://www.facebook.com/agenciawebi/'
        );

        $dados[] = array(
            'titulo' => 'Twitter',
            'campo' => 'link_twitter',
            'tipo' => 'input-text',
            'placeholder' => 'Link para o seu Twitter',
            'dica' => 'Exemplo: http://www.twitter.com/agenciawebi/'
        );

        $dados[] = array(
            'titulo' => 'Google Plus',
            'campo' => 'link_googleplus',
            'tipo' => 'input-text',
            'placeholder' => 'Link para o seu Google Plus',
            'dica' => 'Exemplo: http://plus.google.com/+WebiBrAgenciaDigital/'
        );

        $dados[] = array(
            'titulo' => 'Instagram',
            'campo' => 'link_instagram',
            'tipo' => 'input-text',
            'placeholder' => 'Link para o seu Instagram',
            'dica' => 'Exemplo: http://www.instagram.com/agenciawebi/'
        );

        $dados[] = array(
            'titulo' => 'LinkedIn',
            'campo' => 'link_linkedin',
            'tipo' => 'input-text',
            'placeholder' => 'Link para o seu LinkedIn',
            'dica' => 'Exemplo: http://www.linkedin.com/company/agencia-webi/'
        );

        $dados[] = array(
            'titulo' => 'Youtube',
            'campo' => 'link_youtube',
            'tipo' => 'input-text',
            'placeholder' => 'Link para o seu Youtube',
            'dica' => 'Exemplo: http://www.youtube.com/user/canaldaWEBI'
        );

        $dados[] = array(
            'titulo' => 'ID do Analytics',
            'campo' => 'analytics',
            'tipo' => 'input-text',
            'placeholder' => 'Código de Acompanhamento do Google Analytics',
            'dica' => 'Exemplo: UA-519460-1'
        );

        $dados[] = array(
            'titulo' => 'Endereço de E-mail',
            'campo' => 'end_email',
            'tipo' => 'input-text',
            'placeholder' => 'Digite o e-mail que aparecerá no seu site',
            'dica' => 'Exemplo: contato@webi.com.br'
        );

        $dados[] = array(
            'titulo' => 'Número de Telefone',
            'campo' => 'num_telefone',
            'tipo' => 'input-text',
            'placeholder' => 'Digite o número do telefone que aparecerá no seu site',
            'dica' => 'Exemplo: (48) 3241-3241'
        );

        $dados[] = array(
            'titulo' => 'Número de Whatsapp',
            'campo' => 'num_whatsapp',
            'tipo' => 'input-text',
            'placeholder' => 'Digite o número do Whatsapp que aparecerá no seu site',
            'dica' => 'Exemplo: (48) 9999-9999'
        );

        $dados[] = array(
            'titulo' => 'Texto do Cabeçalho',
            'campo' => 'texto_header',
            'tipo' => 'input-text',
            'placeholder' => 'Texto que aparecerá no Cabeçalho do seu site',
            'dica' => 'Exemplo: Atendemos todo o estado de Santa Catarina'
        );

        $dados[] = array(
            'titulo' => 'Texto do Rodapé',
            'campo' => 'texto_rodape',
            'tipo' => 'input-text',
            'placeholder' => 'Texto que aparecerá no Rodapé do seu site',
            'dica' => 'Exemplo: Copyright 2016 © Todos os Direitos Reservados'
        );

        $dados[] = array(
            'titulo' => 'Publicador',
            'campo' => 'publicador',
            'tipo' => 'input-text',
            'placeholder' => 'Agência WEBI'
        );
        $dados[] = array(
            'titulo' => 'Publicador Link',
            'campo' => 'publicador_link',
            'tipo' => 'input-text',
            'placeholder' => 'http://www.webi.com.br'
        );

        $config = array(
            'listagem' => array('titulo'), // O que vai aparecer na listagem
            'titulo' => "Configurações",
            'ordenar_drag' => true // não dá a opção de ordernar por arrastar
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    /*

      configurações do gercont padrão abaixo

     */

    public function catalogo_categorias() {

        $dados[] = array(
            'titulo' => 'Nome',
            'campo' => 'nome',
            'url_amigavel' => true,
            'tipo' => 'input-text',
            'placeholder' => 'Ex: Sapatos'
        );
        // Imagem
        $miniatures = array();
        $miniaturas[] = array('imagem', 120, 90, true); // criar versão de exatos 350x250px
        $dados[] = array(
            'titulo' => 'Imagem',
            'campo' => 'capa',
            'tipo' => 'imagem',
            'miniaturas' => $miniaturas,
            'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif em 120x90px'
        );

        $titulo = "Categorias do Catálogo"; // Titulo da página
        $listagem = array('nome');

        $config = array(
            'listagem' => array('nome'), // O que vai aparecer na listagem
            'titulo' => "Categorias do Catálogo"
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function catalogo_produtos() {

        $dados[] = array(
            'titulo' => 'Nome',
            'campo' => 'nome',
            'tipo' => 'input-text',
            'url_amigavel' => true,
            'placeholder' => 'Ex: Furadeira Philips 700w 220v'
        );

        $dados[] = array(
            'titulo' => 'Meta keywords',
            'campo' => 'keywords',
            'tipo' => 'input-text',
            'placeholder' => 'palavra chave 1, palavra chave 2, pala...'
        );

        $dados[] = array(
            'titulo' => 'Meta Description',
            'campo' => 'description',
            'tipo' => 'text-area',
            'placeholder' => 'Descrição para resultados nos buscadores',
            'dica' => 'Utilize poucas palavras, serão apresentadas nos resultados dos buscadores'
        );

        // Imagem
        $miniatures = array();
        $miniaturas[] = array('imagem', 800, 600, true); // criar versão de exatos 350x250px
        $dados[] = array(
            'titulo' => 'Imagem',
            'campo' => 'capa',
            'tipo' => 'imagem',
            'miniaturas' => $miniaturas,
            'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif em 800x600px'
        );
        $dados[] = array(
            'titulo' => 'Imagens',
            'campo' => 'imagens',
            'tipo' => 'imagens'
        );
        $dados[] = array(
            'titulo' => 'Breve Descritivo',
            'campo' => 'descritivo',
            'tipo' => 'text-area'
        );
        $dados[] = array(
            'titulo' => 'Descrição Completa',
            'campo' => 'descricao',
            'tipo' => 'text-area-rich'
        );
        $dados[] = array(
            'titulo' => 'Categorias',
            'campo' => 'categoria',
            'tipo' => 'checkbox',
            'dados' => $this->codemin->array_select('catalogo_categorias', 'nome')
        );
        $dados[] = array(
            'titulo' => 'Valor',
            'campo' => 'valor',
            'tipo' => 'monetario'
        );

        $config = array(
            'listagem' => array('nome'), // O que vai aparecer na listagem
            'titulo' => "Produtos do Catálogo"
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function catalogo_configuracao() {

        $dados[] = array(
            'titulo' => 'Email para receber pedidos',
            'campo' => 'email',
            'tipo' => 'input-text',
            'placeholder' => 'contato@webi.com.br'
        );

        $config = array(
            'titulo' => "Configuração do Catálogo"
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function catalogo_listar_pedidos() {

        $this->load->model('catalogo_model');

        $data = array();
        $data['activelink'] = 'catalogo_listar_pedidos';
        $data['scriptFooter'] = array();
        $data['nivel_acesso'] = 0;
        $data['navlinks'] = $this->configuracoes['navlinks'];
        $data['permissoes'] = $this->configuracoes['permissoes'];

        $id = $this->uri->segment(3, null);

        if ($id && $id != "pagina") {

            $data['cliente'] = $this->catalogo_model->detalhes_pedido($id);
            $data['produtos'] = $this->catalogo_model->listar_produtos_pedido($id);
            $this->load->view('codemin/header_view', $data);
            $this->load->view('codemin/nav_view');
            $this->load->view('codemin/catalogo/detalhes_pedido');
            $this->load->view('codemin/footer_view');
        } else {

            $pagina = $this->uri->segment(4, 1);

            $data['pedidos'] = $this->catalogo_model->listar_pedidos($pagina);
            $this->load->view('codemin/header_view', $data);
            $this->load->view('codemin/nav_view');
            $this->load->view('codemin/catalogo/listar_pedidos');
            $this->load->view('codemin/footer_view');
        }
    }

    public function contato() {

        // Idioma
        if ($this->config->item('gercont-multi-idioma')) {
            $array = array_idiomas();
            $dados[] = array(
                'titulo' => 'Idioma',
                'campo' => 'idioma',
                'tipo' => 'radio',
                'dados' => $array
            );
        }
        $dados[] = array(
            'titulo' => 'Email que recebem contato',
            'campo' => 'emails',
            'tipo' => 'text-area',
            'placeholder' => 'bruno@webi.com.br, contato@webi.com.br, rodrigo@webi.com.br',
            'dica' => 'Os emails devem ser separados por vírgula'
        );
        $dados[] = array(
            'titulo' => 'Título da Página',
            'campo' => 'titulo',
            'tipo' => 'input-text',
            'placeholder' => 'Vamos tomar um café? Entre em contato conosco!',
            'dica' => 'Título estára disponível na página de contato'
        );
        $dados[] = array(
            'titulo' => 'Texto de Contato',
            'campo' => 'texto',
            'tipo' => 'text-area',
            'placeholder' => 'Entre em contato conosco pelos telefones (xx) xxxx-xxxx / (xx) xxxx-xxxx ou por email utilizando o formulário ao lado'
        );
        // $dados[] = array(
        //     'titulo' => 'Facebook',
        //     'campo' => 'facebook',
        //     'tipo' => 'input-text',
        //     'placeholder' => 'agenciawebi',
        //     'dica' => 'Somente o que vier após o http://facebook.com/'
        // );
        // $dados[] = array(
        //     'titulo' => 'Twitter',
        //     'campo' => 'twitter',
        //     'tipo' => 'input-text',
        //     'placeholder' => '@agenciawebi',
        //     'dica' => 'Somente o usuário do twitter com @'
        // );
        // $dados[] = array(
        //     'titulo' => 'Localizacao',
        //     'campo' => 'localizacao',
        //     'tipo' => 'google-maps',
        //     'dica' => 'Arraste o marcador vermelho para a localização de sua empresa'
        // );
        // $dados[] = array(
        //     'titulo' => 'Endereço',
        //     'campo' => 'endereco',
        //     'tipo' => 'text-area',
        //     'placeholder' => 'Av. Leoberto Leal, 790 sala 103, 88117-000 São José (Santa Catarina)'
        // );

        $config = array(
            'titulo' => "Contato",
            'listagem' => array('titulo')
        );

        $this->codemin->montar_codemin($dados, $config);
    }

    public function contatos() {

        $this->load->model('gercont_model');

        $data = array();
        $data['activelink'] = 'contatos';
        $data['scriptFooter'] = array();
        $data['nivel_acesso'] = 0;
        $data['navlinks'] = $this->configuracoes['navlinks'];
        $data['permissoes'] = $this->configuracoes['permissoes'];

        $id = $this->uri->segment(3, null);

        if ($id && $id != "pagina") {

            $data['contato'] = $this->gercont_model->detalhes_contato($id);
            $this->load->view('codemin/header_view', $data);
            $this->load->view('codemin/nav_view');
            $this->load->view('codemin/contatos/detalhes_contato');
            $this->load->view('codemin/footer_view');
        } else {

            $pagina = $this->uri->segment(4, 1);

            $data['contatos'] = $this->gercont_model->contatos($pagina);
            $this->load->view('codemin/header_view', $data);
            $this->load->view('codemin/nav_view');
            $this->load->view('codemin/contatos/listar_contatos');
            $this->load->view('codemin/footer_view');
        }
    }

    public function newsletter() {

        $this->load->model('gercont_model');

        $data = array();
        $data['activelink'] = 'contatos';
        $data['scriptFooter'] = array();
        $data['nivel_acesso'] = 0;
        $data['navlinks'] = $this->configuracoes['navlinks'];
        $data['permissoes'] = $this->configuracoes['permissoes'];

        $acao = $this->uri->segment(3, null);

        if ($acao == 'exportar') {

            $tipo = $this->uri->segment(4);
            $this->gercont_model->exportar_newsletter($tipo);
        } else {

            $pagina = $this->uri->segment(4, 1);

            $data['contatos'] = $this->gercont_model->newsletter($pagina);
            $this->load->view('codemin/header_view', $data);
            $this->load->view('codemin/nav_view');
            $this->load->view('codemin/contatos/listar_newsletter');
            $this->load->view('codemin/footer_view');
        }
    }

    public function imagens() {

        if ($this->input->post()) {

            $dados = array(
                'nome' => $this->input->post('name'),
                'largura' => $this->input->post('width'),
                'altura' => $this->input->post('height'),
                'json_pages' => json_encode($this->input->post('json_pages'))
            );

            if ($this->db->where('nome', $dados['nome'])->get('images_config')->num_rows()) {
                $this->db->where('nome', $dados['nome'])->update('images_config', $dados);
            } else {
                $this->db->insert('images_config', $dados);
            }
            redirect('./gercont/imagens');
        } else {

            $config = $this->config;
            $configuracoes = array();
            foreach ($config->config as $key => $value) {
                if (stripos($key, 'images') !== false && $value) {
                    $configuracoes[] = $key;
                }
            }

            $data = array();
            $data['activelink'] = 'configuracoes';
            $data['scriptFooter'] = array();
            $data['nivel_acesso'] = 0;
            $data['navlinks'] = $this->configuracoes['navlinks'];
            $data['permissoes'] = $this->configuracoes['permissoes'];
            $data['configuracoes'] = $configuracoes;
            $data['dados'] = $this->gercont_model->getConfigImages();

            if ($this->uri->segment(3) == 'editar')
                $data['edicao'] = $this->gercont_model->getDefaulValue($this->uri->segment(4));
            elseif ($this->uri->segment(3) == 'excluir') {
                $this->db->where('id', $this->uri->segment(4))->delete('images_config');
                redirect('./gercont/imagens');
            }


            $this->load->view('codemin/header_view', $data);
            $this->load->view('codemin/nav_view');
            $this->load->view('codemin/imagens/imagens' . $this->uri->segment(3));
            $this->load->view('codemin/footer_view');
        }
    }

    /* ================================
     *
     * Não modificar as funções abaixo
     *
     * =============================== */

    private $configuracoes = array();

    // Função padrão, não remover
    public function __construct() {
        parent::__construct();

        $this->load->model('gercont_model');

        $metodosSistema = array('__construct', 'index', 'get_instance', 'login', 'logout', 'usuarios',
            'contato', 'contatos', // $this->config->item('gercont-gerenciar-contato')
            'catalogo_categorias', 'catalogo_produtos', 'catalogo_configuracao', 'catalogo_listar_pedidos', // $this->config->item('gercont-gerenciar-catalogo')
            'newsletter', 'imagens'); // $this->config->item('gercont-gerenciar-newsletter')
        // GERCONT
        if (!$this->config->item('gercont-gerenciar-conteudo'))
            $metodosSistema[] = 'conteudo';
        if (!$this->config->item('gercont-gerenciar-menu'))
            $metodosSistema[] = 'menu';
        if (!$this->config->item('gercont-gerenciar-noticias'))
            $metodosSistema[] = 'noticias';
        if (!$this->config->item('gercont-gerenciar-videos'))
            $metodosSistema[] = 'videos';
        if (!$this->config->item('gercont-gerenciar-fotos'))
            $metodosSistema[] = 'galerias_fotos';
        if (!$this->config->item('gercont-gerenciar-banners'))
            $metodosSistema[] = 'banners';
        if (!$this->config->item('gercont-gerenciar-newsletter'))
            $metodosSistema[] = 'newsletter';
        if (!$this->config->item('gercont-gerenciar-contato')) {
            $metodosSistema[] = 'contato';
            $metodosSistema[] = 'contatos';
        }
        if (!$this->config->item('gercont-gerenciar-catalogo')) {
            $metodosSistema[] = 'catalogo_categorias';
            $metodosSistema[] = 'catalogo_produtos';
            $metodosSistema[] = 'catalogo_configuracao';
            $metodosSistema[] = 'catalogo_listar_pedidos';
        }
        // GERCONT

        $areas = array();
        $navlinks = array();
        $configuracoes = array();

        if ($this->session->userdata('administrador')) {

            foreach (get_class_methods($this) as $metodo) {
                if (!in_array($metodo, $metodosSistema)) {
                    $areas[$metodo] = str_replace("_", " ", ucfirst($metodo));
                    $navlinks[$metodo] = str_replace("_", " ", ucfirst($metodo));
                }
            }

            $permissoes = array();
            $configuracoes['permissoes'] = $permissoes;
        } else {

            $this->db->where('id_usuario', $this->session->userdata('id'));
            $this->db->where('nivel >', 0);
            $acessos = $this->db->get('codemin_usuarios_permissoes')->result();

            $permissoes = array();
            foreach ($acessos as $acesso) {
                $permissoes[] = $acesso->area;
            }

            $configuracoes['permissoes'] = $permissoes;

            foreach (get_class_methods($this) as $metodo) {
                if (!in_array($metodo, $metodosSistema)) {
                    $areas[$metodo] = str_replace("_", " ", ucfirst($metodo));
                }
                if (!in_array($metodo, $metodosSistema) & in_array($metodo, $permissoes)) {
                    $navlinks[$metodo] = str_replace("_", " ", ucfirst($metodo));
                }
            }
        }

        if ($this->config->item('gercont-gerenciar-contato')) {
            $areas['contato'] = 'Configurar Contato';
            $areas['contatos'] = 'Listar Contatos';
        }
        if ($this->config->item('gercont-gerenciar-catalogo')) {
            $areas['catalogo_produtos'] = 'Produtos';
            $areas['catalogo_categorias'] = 'Categorias';
            $areas['catalogo_configuracao'] = 'Configuração';
            $areas['catalogo_listar_pedidos'] = 'Listar Pedidos';
        }
        if ($this->config->item('gercont-gerenciar-newsletter')) {
            $areas['newsletter'] = 'Newsletter';
        }

        $configuracoes['areas'] = $areas;
        $configuracoes['navlinks'] = $navlinks;
        $configuracoes['activelink'] = $this->uri->segment(2, null);

        $this->configuracoes = $configuracoes;
        $this->load->library('codemin/codemin', $configuracoes);
    }

    // Função padrão, não remover
    public function index() {

        $this->load->model('catalogo_model');

        $data['activelink'] = null;
        $data['scriptFooter'] = array();
        $data['nivel_acesso'] = 0;
        $data['navlinks'] = $this->configuracoes['navlinks'];
        $data['permissoes'] = $this->configuracoes['permissoes'];
        $data['log_acoes'] = $this->codemin_model->pegar_log_acoes(15, true);
        $data['minhas_acoes'] = $this->codemin_model->pegar_log_acoes(15);
        $data['log_acessos'] = $this->codemin_model->pegar_log_acessos(15, true);
        $data['meus_acessos'] = $this->codemin_model->pegar_log_acessos(15);

        if (in_array('contatos', $this->configuracoes['permissoes']) || $this->session->userdata('administrador'))
            $data['novos_contatos'] = $this->gercont_model->pegar_novos_contatos(5);

        if (in_array('newsletter', $this->configuracoes['permissoes']) || $this->session->userdata('administrador'))
            $data['novos_newsletters'] = $this->gercont_model->pegar_novos_newsletters(5);

        if (in_array('catalogo_listar_pedidos', $this->configuracoes['permissoes']) || $this->session->userdata('administrador'))
            $data['novos_pedidos'] = $this->catalogo_model->pegar_novos_pedidos(5);

        $this->load->view('codemin/header_view', $data);
        $this->load->view('codemin/nav_view');
        $this->load->view('codemin/dashboard_view');
        $this->load->view('codemin/footer_view');
    }

    // Função padrão, não remover
    public function usuarios() {

        $titulo = "Usuários";
        $this->codemin->montar_codemin_usuarios($titulo);
    }

    // Função padrão, não remover
    public function login() {

        $data = array();

        $data['scriptFooter'] = array();
        $data['nivel_acesso'] = 0;

        if ($this->input->post('enviado')) {

            $login = $this->input->post('login');
            $senha = $this->input->post('senha');

            if ($this->codemin_model->fazer_login($login, $senha)) {
                redirect(base_url() . $this->uri->segment(1), 'location');
            } else {
                $data['erro'] = false;
                $data['login'] = $login;
            }
        }

        $this->load->view('codemin/header_view', $data);
        $this->load->view('codemin/login_view');
        $this->load->view('codemin/footer_view');
    }

    // Função padrão, não remover
    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url() . $this->uri->segment(1) . '/login', 'location');
    }

}
