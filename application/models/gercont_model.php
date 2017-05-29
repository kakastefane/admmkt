<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gercont_model extends CI_Model {

    private $configuracoes = array();

    /**
     *
     * Função construct, descrever as funções caso tenha
     * @param null
     * @return null
     *
     * */
    public function __construct() {
        parent::__construct();

        $get_idioma = $this->input->get('idioma');
        if ($get_idioma) {
            $this->session->set_userdata('idioma', id_idioma($get_idioma));
        }

        $idioma_id = $this->session->userdata('idioma');
        if ($idioma_id) {
            $this->configuracoes['idioma'] = $idioma_id;
        } else {
            $this->configuracoes['idioma'] = $idioma_id = $this->config->item('gercont-idioma-padrao');
        }

        $idiomas = array_idiomas(true);
        $this->lang->load('gercont', $idiomas[$idioma_id]);
    }

    /**
     * conteudo
     *
     * Retorna um resultado com o conteúdo
     * @param string
     * @return object
     *
     * */
    public function conteudo($url) {

        if ($this->config->item('gercont-multi-idioma'))
            $this->db->where('idioma', $this->configuracoes['idioma']);

        $this->db->limit(1);
        $this->db->where('ativo', 1);
        $this->db->where('url_titulo', $url);
        return $this->db->get('conteudo')->row();
    }

    /**
     * conteudos
     *
     * Retorna os conteúdos de um menu
     * @param string
     * @return object_array
     *
     * */
    public function conteudos($url) {

        if ($this->config->item('gercont-multi-idioma'))
            $this->db->where('idioma', $this->configuracoes['idioma']);

        $this->db->limit(1);
        $this->db->where('ativo', 1);
        $this->db->where('funcao', 1); // Retorna menu com link de conteúdo
        $this->db->where('url_titulo', $url);
        $menu = $this->db->get('menu');

        if ($menu->num_rows() >= 1) {

            $conteudos = array();
            $menu = $menu->row();
            $this->db->where('id_registro', $menu->id);
            $this->db->where('tabela', 'menu');
            $this->db->where('campo', 'link_conteudo');
            foreach ($this->db->get('codemin_opcoes_selecionadas')->result() as $opcao) {
                $conteudos[] = $opcao->id_opcao;
            }

            if ($this->config->item('gercont-multi-idioma'))
                $this->db->where('idioma', $this->configuracoes['idioma']);

            $this->db->where('ativo', 1);
            $this->db->where_in('id', $conteudos);
            $this->db->order_by('Titulo', 'ASC');
            return $this->db->get('conteudo')->result();
        } else {
            return false;
        }
    }

    /**
     * detalhes
     *
     * Retorna um resultado com as configurações do site
     * @param null
     * @return object
     *
     * */
    public function detalhes($tabela, $campo, $namespace, $use_idioma = true) {

        if ($this->config->item('gercont-multi-idioma') & $use_idioma)
            $this->db->where('idioma', $this->configuracoes['idioma']);

        $this->db->limit(1);
        $this->db->where('ativo', 1);
        $this->db->where($campo, $namespace);
        return $this->db->get($tabela)->row();
    }

    /**
     * contatos
     *
     * Retorna todos os contatos feitos pelo site
     * @param int
     * @return obj_array
     *
     * */
    public function contatos($pagina = 1) {

        $por_pagina = 50;

        if ($pagina == 1) {
            $incial = 0;
        } else {
            $incial = $por_pagina * ($pagina - 1);
        }

        $this->load->library('pagination');
        $config['base_url'] = './administrador/contatos/pagina/';
        $config['uri_segment'] = 4;
        $config['total_rows'] = $this->db->count_all('codemin_contatos');
        $config['per_page'] = $por_pagina;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $this->db->limit($por_pagina, $incial);

        $this->db->order_by('data_hora', 'DESC');
        return $this->db->get('codemin_contatos')->result();
    }

    /**
     * detalhes_contato
     *
     * Retorna todos os detalhes de um contato feito pelo site
     * @param int
     * @return object
     *
     * */
    public function detalhes_contato($id) {

        $this->db->where('id', $id);
        $contato = $this->db->get('codemin_contatos')->row();

        if ($contato->visualizado == 0) {
            $this->db->where('id', $id);
            $update = array('visualizado' => 1);
            $this->db->update('codemin_contatos', $update);
        }

        return $contato;
    }

    /**
     * pegar_novos_contatos
     *
     * Pega os novos contatos (não visualizados)
     * @param int
     * @return object
     *
     * */
    public function pegar_novos_contatos($limit) {

        $this->db->limit($limit);
        $this->db->where('visualizado', 0);
        $this->db->order_by('data_hora', 'DESC');
        return $this->db->get('codemin_contatos')->result();
    }

    /**
     * newsletter
     *
     * Retorna todos os newsletter cadastrados pelo site
     * @param int
     * @return obj_array
     *
     * */
    public function newsletter($pagina = 1) {

        $por_pagina = 50;

        if ($pagina == 1) {
            $incial = 0;
        } else {
            $incial = $por_pagina * ($pagina - 1);
        }

        $this->load->library('pagination');
        $config['base_url'] = './administrador/newsletter/pagina/';
        $config['uri_segment'] = 4;
        $config['total_rows'] = $this->db->count_all('codemin_newsletter');
        $config['per_page'] = $por_pagina;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $this->db->limit($por_pagina, $incial);

        $this->db->order_by('data_hora', 'DESC');
        return $this->db->get('codemin_newsletter')->result();
    }

    /**
     * exportar_newsletter
     *
     * Exporta os newsletters cadastrados pelo site
     * @param string
     * @return obj_array
     *
     * */
    public function exportar_newsletter($tipo) {

        $this->db->select('nome, email');

        switch ($tipo) {
            case 'todos':
                $this->db->order_by('data_hora', 'DESC');
                $newsletters = $this->db->get('codemin_newsletter')->result_array();
                break;

            default:
                $this->db->where('exportado', 0);
                $this->db->order_by('data_hora', 'DESC');
                $newsletters = $this->db->get('codemin_newsletter')->result_array();
                break;
        }

        $update = array('exportado' => 1);
        $this->db->update('codemin_newsletter', $update);

        download_send_headers("data_export_" . date("Y-m-d") . ".csv");
        echo array2csv($newsletters);
        die();
    }

    /**
     * pegar_novos_newsletters
     *
     * Pega os novos newsletters (não exportados)
     * @param int
     * @return object
     *
     * */
    public function pegar_novos_newsletters($limit) {

        $this->db->limit($limit);
        $this->db->where('exportado', 0);
        $this->db->order_by('data_hora', 'DESC');
        return $this->db->get('codemin_newsletter')->result();
    }

    /**
     * contato
     *
     * Retorna um resultado com as informações de contato do site
     * @param null
     * @return object
     *
     * */
    public function contato() {

        if ($this->config->item('gercont-multi-idioma'))
            $this->db->where('idioma', $this->configuracoes['idioma']);

        $this->db->limit(1);
        $this->db->where('ativo', 1);
        $this->db->order_by('ordenar', 'asc');
        return $this->db->get('contato')->row();
    }

    /**
     * configuracoes
     *
     * Retorna um resultado com as configurações do site
     * @param null
     * @return object
     *
     * */
    public function configuracoes() {

        if ($this->config->item('gercont-multi-idioma'))
            $this->db->where('idioma', $this->configuracoes['idioma']);

        $this->db->limit(1);
        $this->db->where('ativo', 1);
        $this->db->order_by('ordenar', 'asc');
        $conteudo = $this->db->get('configuracoes')->row();

        $conteudo->menu = $this->menu();
        return $conteudo;
    }

    /**
     * menu
     *
     * Retorna uma string com o html pronto do menu
     * @param null
     * @return string
     *
     * */
    private function menu() {

        $menus = "<ul class='menu_header'>"; // UL PRINCIPAL


        if ($this->config->item('gercont-multi-idioma'))
            $this->db->where('idioma', $this->configuracoes['idioma']);

        $this->db->where('ativo', 1);
        $this->db->where('menu_pai', 0);
        $this->db->order_by('ordenar', 'asc');
        $menus_pais = $this->db->get('menu')->result();

        foreach ($menus_pais as $menu_pai) {

            $target = $menu_pai->abrir ? 'target="_blank"' : '';

            switch ($menu_pai->funcao) {
                case 0:
                    $menus .= "<li><a $target href='$menu_pai->link_manual'>$menu_pai->titulo</a>"; // LI PAI
                    break;

                case 1:
                    $menus .= $this->pegar_menu_conteudo($menu_pai->id);
                    break;

                case 2:
                    $rota = rotas_do_sistema($menu_pai->link_gercont);
                    $menus .= "<li><a $target href='" . $this->lang->line($rota) . "'>$menu_pai->titulo</a>";
                    break;
            }


            if ($this->config->item('gercont-multi-idioma'))
                $this->db->where('idioma', $this->configuracoes['idioma']);

            $this->db->where('ativo', 1);
            $this->db->where('menu_pai', $menu_pai->id);
            $this->db->order_by('ordenar', 'asc');
            $menus_filhos = $this->db->get('menu')->result();

            $menus .= "<ul class='drop'>"; // UL DOS FILHOS

            foreach ($menus_filhos as $menu_filho) {

                switch ($menu_filho->funcao) {
                    case 0:
                        $menus .= "<li><a href='$menu_filho->link_manual'>$menu_filho->titulo</a>"; // LI FILHO
                        break;

                    case 1:
                        $menus .= $this->pegar_menu_conteudo($menu_filho->id);
                        break;
                        
                    case 2:
                        $rota = rotas_do_sistema($menu_filho->link_gercont);
                        $menus .= "<li><a $target href='" . $this->lang->line($rota) . "'>$menu_filho->titulo</a>";
                        break;
                }

                if ($this->config->item('gercont-multi-idioma'))
                    $this->db->where('idioma', $this->configuracoes['idioma']);

                $this->db->where('ativo', 1);
                $this->db->where('menu_pai', $menu_filho->id);
                $this->db->order_by('ordenar', 'asc');
                $menus_netos = $this->db->get('menu')->result();

                $menus .= "<ul class='down'>"; // UL DOS NETOS

                foreach ($menus_netos as $menu_neto) {

                    switch ($menu_neto->link_manual) {
                        case 0:
                            $menus .= "<li><a href='$menu_neto->link_manual'>$menu_neto->titulo</a>"; // LI NETO
                            break;

                        case 1:
                            $menus .= $this->pegar_menu_conteudo($menu_neto->id);
                            break;
                    }
                }

                $menus .= "</ul>"; // UL DOS NETOS

                $menus .= "</li>"; // LI FILHO
            }

            $menus .= "</ul>"; // UL DOS FILHOS

            $menus .= "</li>"; // LI PAI
        }

        $menus .= "</ul>"; // UL

        return $menus;
    }

    // EXTRAS
    // EXTRAS

    /**
     * pegar_menu_conteudo
     *
     * Retorna um array com os menus criados
     * @param null
     * @return array
     *
     * */
    public function pegar_menu_conteudo($id) {

        // Busca as informações dos conteúdos
        $this->db->where('id_registro', $id);
        $this->db->where('tabela', 'menu');
        $this->db->where('campo', 'link_conteudo');
        $conteudos = $this->db->get('codemin_opcoes_selecionadas');

        // Busca as informações do menu
        $this->db->where('id', $id);
        $menu = $this->db->get('menu')->row();

        $target = $menu->abrir > 0 ? 'target="_blank"' : '';

        if ($conteudos->num_rows() > 1) { // Se tiver mais que um conteúdo relacionado ao menu, mostra o link para a listagem dos conteúdos
            return "<li><a $target href='./" . $this->lang->line('rota_conteudo') . "/$menu->url_titulo'>$menu->titulo</a>";
        } else { // Se tiver somente um conteúdo relacionado ao menu, mostra o link direto para o menu
            $conteudo_id = $conteudos->row()->id_opcao;
            $this->db->where('id', $conteudo_id);
            $conteudo = $this->db->get('conteudo')->row();
            return "<li><a $target href='./$conteudo->url_titulo'>$menu->titulo</a>";
        }
    }

    /**
     * menu_select
     *
     * Retorna um array com os menus criados
     * @param null
     * @return array
     *
     * */
    public function menu_select() {

        $return = array();
        $return[0] = 'Nenhum';

        $this->db->where('ativo', 1);
        foreach ($this->db->get('menu')->result() as $menu) {
            $return[$menu->id] = $menu->titulo;
        }

        return $return;
    }

    /**
     * conteudos
     *
     * Retorna um array com todos os conteúdos
     * @param null
     * @return array
     *
     * */
    public function conteudos_select() {

        $return = array();

        $this->db->where('ativo', 1);
        $this->db->order_by('titulo', 'asc');
        foreach ($this->db->get('conteudo')->result() as $conteudo) {
            $return[$conteudo->id] = $conteudo->titulo;
        }

        return $return;
    }

    public function getConfigImages() {
        return $this->db->get('images_config')->result();
    }

    public function getDefaulValue($param) {
        return $this->db->where('id', $param)->get('images_config')->row();
    }

}
