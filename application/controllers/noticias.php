<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * DESENVOLVIDO POR BRUNO ALMEIDA
 *
 * DEZEMBRO DE 2013
 */

class Noticias extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gercont_model');
    }

    /**
     *
     * todas
     * 
     * Lista todas as notícias
     * 
     *
     * @access	public
     * @param	null
     * @return	null
     */
    public function todas() {

        $data = array();

        $data['configuracoes'] = $this->gercont_model->configuracoes();
        $data['titulo'] = "Notícias";

        $this->load->view('includes/header', $data);
        $this->load->view('noticias/listar');
        $this->load->view('includes/footer');
    }

    /**
     *
     * todas
     * 
     * Trás os detalhes de uma notícia
     * 
     *
     * @access	public
     * @param	null
     * @return	null
     */
    public function detalhes() {

        $url = $this->uri->segment(2);

        $data = array();

        $data['configuracoes'] = $this->gercont_model->configuracoes();
        $data['detalhe'] = $this->gercont_model->detalhes('noticias', 'url_titulo', $url);
        $data['titulo'] = $data['detalhe']->titulo;
        $data['pgkeywords'] = $data['detalhe']->keywords;
        $data['pgdescription'] = $data['detalhe']->description;
        $data['fbimg'] = base_url('uploads/noticias/imagens/' . $data['detalhe']->id . '/' . $data['detalhe']->capa);

        if (!$data['detalhe']) {
            $this->erro_404();
        } else {
            $this->load->view('includes/header', $data);
            $this->load->view('noticias/detalhes');
            $this->load->view('includes/footer');
        }
    }

    /**
     *
     * erro_404
     * 
     * Exibi a página não encontrada
     * 
     *
     * @access	private
     * @param	null
     * @return	null
     */
    private function erro_404() {

        $data['configuracoes'] = $this->gercont_model->configuracoes();
        $data['titulo'] = "Página não encontrada";

        $this->load->view('includes/header', $data);
        $this->load->view('erro_404');
        $this->load->view('includes/footer');
    }

}
