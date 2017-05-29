<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * DESENVOLVIDO POR BRUNO ALMEIDA
 *
 * DEZEMBRO DE 2013
 */

class Galerias_fotos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gercont_model');
    }

    /**
     *
     * index
     * 
     * Redireciona para a função todas
     * 
     *
     * @access	public
     * @param	null
     * @return	null
     */
    public function index() {
        $this->todas;
    }

    /**
     *
     * todas
     * 
     * Lista todas as galerias de fotos
     * 
     *
     * @access	public
     * @param	null
     * @return	null
     */
    public function todas() {

        $data = array();

        $data['configuracoes'] = $this->gercont_model->configuracoes();
        $data['titulo'] = "Galerias de Fotos";

        $this->load->view('includes/header', $data);
        $this->load->view('galerias_fotos/listar');
        $this->load->view('includes/footer');
    }

    /**
     *
     * detalhes
     * 
     * Trás os detalhes de uma galeria de fotos
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
        $data['detalhe'] = $this->gercont_model->detalhes('galerias_fotos', 'url_nome', $url);
        $data['titulo'] = $data['detalhe']->nome;
        $data['fbimg'] = base_url('uploads/galerias_fotos/imagens/' . $data['detalhe']->id . '/' . $data['detalhe']->capa);
        $data['pgkeywords'] = $data['detalhe']->keywords;
        $data['pgdescription'] = $data['detalhe']->description;

        if (!$data['detalhe']) {
            $this->erro_404();
        } else {
            $this->load->view('includes/header', $data);
            $this->load->view('galerias_fotos/detalhes');
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
