<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Codemin_model extends CI_Model {

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
        $this->load->library('Form_validation');
        // $this->configuracoes['key'] = 'value';
    }

    /**
     *
     * verificar_login
     * 
     * Verifica se o usuário logado mantém o mesmo id, login e senha
     * 
     *
     * @access	public
     * @param	null
     * @return	boolean
     */
    public function verificar_login() {

        if ($this->session->userdata('logado')) {

            $this->db->where('id', $this->session->userdata('id'));
            $this->db->where('login', $this->session->userdata('login'));
            $this->db->where('senha', $this->session->userdata('senha'));
            $this->db->where('ativo', 1);

            if ($this->db->get('codemin_usuarios')->num_rows() == 1) {
                return true;
            }
        }
    }

    /**
     *
     * fazer_login
     * 
     * Verifica se o usuário existe pelo login e senha
     * 
     *
     * @access	public
     * @param	$string, $string
     * @return	boolean
     */
    public function fazer_login($login, $senha) {

        $this->db->where('ativo', 1);
        $this->db->where('login', $login);
        $this->db->where('senha', senha_usuario($senha));

        $retorno = $this->db->get('codemin_usuarios');

        if ($retorno->num_rows == 1) {

            $usuario = $retorno->row();
            $sessao = array(
                'id' => $usuario->id,
                'login' => $usuario->login,
                'senha' => $usuario->senha,
                'nome' => $usuario->nome,
                'administrador' => $usuario->administrador,
                'logado' => TRUE
            );
            $this->session->set_userdata($sessao);

            $this->log_acessos();

            return true;
        }
    }

    /**
     *
     * verificar_permissao
     * 
     * Verifica qual o invel de permissão do usuário
     * 
     *
     * @access	public
     * @param	null
     * @return	int
     */
    public function verificar_permissao() {

        if ($this->session->userdata('administrador')) {
            return 3;
        }

        $area = $this->uri->segment(2);

        $this->db->where('id_usuario', $this->session->userdata('id'));
        $this->db->where('area', $area);
        return $this->db->get('codemin_usuarios_permissoes')->row()->nivel;
    }
    
    public function gerarSiteMap($tabela)
    {
    
        $sitemap  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL . PHP_EOL;

        if($tabela == 'noticias' || $tabela == 'conteudo'){

            $array = array('conteudo','noticias');

            foreach($array AS $table){

                foreach($this->db->get_where($table,array('ativo' => 1))->result() AS $linha){

                    if( array_key_exists('url_titulo', $linha) ){

                        if( $this->lang->line('rota_' . $tabela) == 'conteudo' ){ 
                            $slug = base_url() . $linha->url_titulo;
                        } else { 
                            $slug = base_url() . $this->lang->line('rota_' . $tabela) . '/' . $linha->url_titulo;
                        }

                        $sitemap .= '<url>' . PHP_EOL;
                        $sitemap .= ' <loc>'.$slug.'</loc>' . PHP_EOL;
                        $sitemap .= ' <changefreq>weekly</changefreq>' . PHP_EOL;
                        $sitemap .= '</url>' . PHP_EOL . PHP_EOL;

                    }

                }

            }

            $sitemap .= '</urlset>';

            $h = @fopen('sitemap.xml','w+');
            @fwrite($h, $sitemap);
            @fclose($h);

        }

    }

    /**
     *
     * adicionar
     * 
     * Verificar se tem um POST e insere no banco
     * 
     *
     * @access	public
     * @param	array, int
     * @return	boolean
     */
    public function adicionar($dados, $nivel_acesso) {

        //$this->load->library('form_validation');
        //$this->load->library('MY_Form_validation');

        foreach ($dados as $dado) {
            $rules = null;
            if (isset($dado['validacao'])) {
                $rules = $dado['validacao'];
                $validacao = true;
                $this->form_validation->set_rules($dado['campo'], $dado['titulo'], $rules);
            }
        }

        $resultado_validacao = null;
        if (isset($validacao)) {
            $resultado_validacao = $this->form_validation->run();
        }

        // Se tiver um POST insere no banco
        if ($_POST & $resultado_validacao !== false) {

            $tabela = $this->uri->segment(2);
            $inserir = array();

            // Insere o id do usuário
            $inserir['id_usuario'] = $this->session->userdata('id');

            // Grava os dados no banco			
            foreach ($dados as $dado) {

                $campo = $dado['campo'];
                $tipo = $dado['tipo'];

                $url_amigavel = null;
                if (isset($dado['url_amigavel'])) {
                    $url_amigavel = $dado['url_amigavel'];
                }

                if ($tipo == 'imagem' || $tipo == 'arquivo') {
                    $uploads[] = $dado;
                } elseif ($tipo == 'checkbox' || $tipo == 'checkbox-dinamico' || $tipo == 'multiple') {
                    $array_campo = $this->input->post($campo);
                    if (!is_array($array_campo) && $tipo == 'multiple') {
                        $array_campo = array($array_campo);
                    }
                    $opcoes_selecionadas[$campo] = $array_campo;
                } else {
                    $inserir[$campo] = $this->converter_para_banco($this->input->post($campo), $tipo, $campo);
                    if ($url_amigavel) {
                        $inserir['url_' . $campo] = $this->gerar_url_amigavel($tabela, $campo, $inserir[$campo]);
                    }
                }
            }


            if ($nivel_acesso >= 2) {
                $inserir['ativo'] = $this->input->post('ativo');
            }
            
            if (array_key_exists('senha', $inserir) && !empty($inserir['senha'])) {
                $inserir['senha'] = senha_usuario($inserir['senha']);
            }

            if ($this->db->insert($tabela, $inserir)) {
            
                $this->gerarSiteMap($tabela);

                $id = $this->db->insert_id();

                // Faz o upload no diretório correto, já com ID
                if (isset($uploads) || isset($opcoes_selecionadas)) {

                    if (isset($uploads)) {

                        foreach ($uploads as $dado) {
                            $campo = $dado['campo'];
                            $tipo = $dado['tipo'];
                            $miniaturas = $dado['miniaturas'];
                            $atualizar[$campo] = $this->converter_para_banco($this->input->post($campo), $tipo, $campo, $id, $miniaturas);
                        }

                        // Atualiza os dados na tabela com os arquivos upados
                        $this->db->where('id', $id);
                        $this->db->update($tabela, $atualizar);
                    }
                    if (isset($opcoes_selecionadas)) {
                        foreach ($opcoes_selecionadas as $campo => $opcoes) {
                            $this->gravar_opcoes_selecionadas($opcoes, $campo, $id);
                        }
                    }

                    $this->log_acoes($id, 1);
                    return true;
                } else {
                    $this->log_acoes($id, 1);
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    /**
     *
     * editar
     * 
     * Verificar se tem um POST e insere no banco
     * 
     *
     * @access	public
     * @param	array, int
     * @return	boolean
     */
    public function editar($dados, $nivel_acesso) {

        $id_usuario = $this->session->userdata('id');

        // Pegar id e tabela
        $id = $this->uri->segment(4, 1);
        $tabela = $this->uri->segment(2);

        //$this->load->library('form_validation');
        foreach ($dados as $dado) {
            $rules = null;
            if (isset($dado['validacao'])) {
                $rules = $dado['validacao'];
                $validacao = true;
            }
            $this->form_validation->set_rules($dado['campo'], $dado['campo'], $rules . '');
        }

        $resulatado_validacao = null;
        if (isset($validacao)) {
            $resulatado_validacao = $this->form_validation->run();
        }

        // Se tiver POST atualiza
        if ($_POST & $resulatado_validacao !== FALSE) {

            $atualizar = array();
            foreach ($dados as $dado) {
                $campo = $dado['campo'];
                $tipo = $dado['tipo'];

                $url_amigavel = null;
                if (isset($dado['url_amigavel'])) {
                    $url_amigavel = $dado['url_amigavel'];
                }

                $miniaturas = array();
                if (isset($dado['miniaturas'])) {
                    $miniaturas = $dado['miniaturas'];
                }

                if ($tipo == 'checkbox' || $tipo == 'checkbox-dinamico' || $tipo == 'multiple') {
                    $this->gravar_opcoes_selecionadas($this->input->post($campo), $campo, $id);
                } else {
                    $retorno = $this->converter_para_banco($this->input->post($campo), $tipo, $campo, null, $miniaturas);
                }

                if ($tipo == 'checkbox-unico') {
                    $retorno = ($this->input->post($campo) == 'on') ? 1 : 0;
                }

                // Confere se não foi enviado imagem, se não foi enviado não insere
                if ((!$retorno AND $tipo != 'imagem' AND $tipo != 'arquivo') OR ( $retorno)) {
                    $atualizar[$campo] = $retorno;
                    if ($url_amigavel) {
                        $atualizar['url_' . $campo] = $this->gerar_url_amigavel($tabela, $campo, $atualizar[$campo], $id);
                    }
                }
            }

            // Se o usuário tiver permissão para ativar o item
            if ($nivel_acesso >= 2) {
                $atualizar['ativo'] = $this->input->post('ativo');
            }

            // Se o usuário não tiver acesso total ou for configurações do usuário,
            // edita somente os ítens dele
            if ($nivel_acesso < 3) {
                $this->db->where('id', $id);
                $this->db->where('id_usuario', $id_usuario);
            } elseif (isset($usuario)) {
                $this->db->where('id_usuario', $id_usuario);
            }

            // Verifica se são configurações do usuário

            if (array_key_exists('senha', $atualizar) && !empty($atualizar['senha'])) {
                $atualizar['senha'] = senha_usuario($atualizar['senha']);
            }

            if (isset($usuario)) {
                $this->db->where('id_usuario', $id_usuario);

                if ($this->db->get($tabela)->num_rows() > 0) {
                    $this->db->where('id_usuario', $id_usuario);
                    $atualizar = $this->db->update($tabela, $atualizar);
                } else {
                    $atualizar['id_usuario'] = $id_usuario;
                    $atualizar = $this->db->insert($tabela, $atualizar);
                }

                // Se forem configurações do sistema atualiza o id 1
            } else {
                $this->db->where('id', $id);
                $atualizar = $this->db->update($tabela, $atualizar);
            }

            if ($atualizar) {
                $this->gerarSiteMap($tabela);
                $this->log_acoes($id, 2);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     *
     * status_ativo
     * 
     * Verificar se tem um POST e insere no banco
     * 
     *
     * @access	public
     * @param	int
     * @return	boolean
     */
    public function status_ativo($nivel_acesso) {

        $resultado = array();

        $id_usuario = $this->session->userdata('id');

        // Pegar id e tabela
        $id = $this->uri->segment(4, 1);
        $tabela = $this->uri->segment(2);

        // Se o usuário tiver permissão para ativar o item
        if ($nivel_acesso >= 2) {

            $this->db->where('id', $id);
            $ativo = $this->db->get($tabela)->row()->ativo;

            if ($ativo == 1) {
                $atualizar['ativo'] = 0;
                $resultado['ativo'] = false;
            } else {
                $atualizar['ativo'] = 1;
                $resultado['ativo'] = true;
            }

            // Se o usuário não tiver acesso total ou for configurações do usuário,
            // edita somente os ítens dele
            if ($nivel_acesso < 3) {
                $this->db->where('id', $id);
                $this->db->where('id_usuario', $id_usuario);
            } elseif (isset($usuario)) {
                $this->db->where('id_usuario', $id_usuario);
            }

            $this->db->where('id', $id);
            $atualizar = $this->db->update($tabela, $atualizar);

            if ($atualizar) {
                $this->log_acoes($id, 2);
                $resultado['sucesso'] = true;
            } else {
                $resultado['sucesso'] = false;
            }
        } else {
            $resultado['sucesso'] = false;
        }

        return $resultado;
    }

    /**
     * excluir
     * 
     * Excluiu um registro do banco de dados
     * 
     *
     * @access	public
     * @param	int
     * @return	boolean
     */
    public function excluir($nivel_acesso) {

        $id_usuario = $this->session->userdata('id');

        $id = $this->uri->segment(4);
        $tabela = $this->uri->segment(2);

        if ($nivel_acesso < 3) { // Se o usuário não tiver acesso total, excluí somente os ítens dele
            $this->db->where('id_usuario', $id_usuario);
        }
        $this->db->where('id', $id);
        if ($this->db->update($tabela, array('ativo' => 2))) {
            // if($this->db->delete($tabela)){
            $this->log_acoes($id, 3);
            return true;
        } else {
            return false;
        }
    }

    /**
     * resultado
     * 
     * Pega um único resultado do banco de dados
     * 
     *
     * @access	public
     * @param	int
     * @return	obj
     */
    public function resultado($nivel_acesso) {

        $id_usuario = $this->session->userdata('id');

        // Pegar id e tabela
        $id = $this->uri->segment(4, 1);
        $tabela = $this->uri->segment(2);

        // Busca os resultados atuais no banco de dados
        // Se o usuário não tiver acesso total ou for configurações do usuário,
        // mostra somente os ítens dele
        if ($nivel_acesso < 3) {
            $this->db->where($tabela . '.id_usuario', $id_usuario);
            $this->db->where($tabela . '.id', $id);
        } elseif (isset($usuario)) {
            $this->db->where($tabela . '.id_usuario', $id_usuario);
        } else {
            $this->db->where($tabela . '.id', $id);
        }

        $this->db->where($tabela . '.ativo !=', 2); // Pseudo excluído

        return $this->db->get($tabela)->row_array();
    }

    /**
     * resultados
     * 
     * Pega todos os resultados do banco de dados
     * 
     *
     * @access	public
     * @param	int
     * @return	obj
     */
    public function resultados($config, $nivel_acesso) {

        $return = new stdClass();

        $id_usuario = $this->session->userdata('id');
        $tabela = $this->uri->segment(2);

        if (isset($config['order_by']))
            foreach ($config['order_by'] as $key => $value)
                $this->db->order_by($key, $value);

        $this->db->where($tabela . '.ativo !=', 2); // Pseudo excluído

        /*
         * Faz a conversão dos dados a serem exibidos
         * e faz o join da tabela para exibir os campos
         */
        foreach ($config['listagem'] as $item) {
            if (is_array($item)) {

                $tabela_estrangeira = $item['tabela'];
                $campo_estrangeiro = $item['campo_estrangeiro'];
                $chave_estrangeira = $item['chave_estrangeira'];
                $novo_titulo = $item['titulo'];
                $campo = isset($item['campo']) ? $item['campo'] : 'id';
                $nova_tabela = isset($item['nova_tabela']) ? $item['nova_tabela'] : $tabela_estrangeira;

                $this->db->select($tabela_estrangeira . '.' . $campo_estrangeiro . ' as ' . $novo_titulo);
                $this->db->join($tabela_estrangeira . ' as ' . $nova_tabela, $nova_tabela . '.' . $campo . ' = ' . $tabela . '.' . $chave_estrangeira, 'LEFT');

                $listagens[] = $novo_titulo;
            } else {
                $listagens[] = $item;
            }
        }

        $this->db->select('codemin_usuarios.nome as nome_usuario');
        $this->db->select($tabela . '.*');
        $this->db->join('codemin_usuarios', 'codemin_usuarios.id = ' . $tabela . '.id_usuario', 'LEFT');

        // Pega todos os resultados
        if ($nivel_acesso < 3) { // Se o usuário não tiver acesso total, mostra somente os ítens dele
            $this->db->where($tabela . '.id_usuario', $id_usuario);
        }

        $this->db->order_by($tabela . '.ordenar', 'asc');
        $this->db->order_by($tabela . '.id', 'desc');

        $return->resultados = $this->db->get($tabela)->result_array();
        $return->listagens = $listagens;
        return $return;
    }

    /**
     * converter_para_banco
     * 
     * Converte a string para o formato do db
     * quando houver necessidade
     * 
     *
     * @access	private
     * @param	$string, $string, $string, $string, $array
     * @return	$string
     */
    private function converter_para_banco($valor, $tipo, $name = null, $id = null, $miniaturas = null) {
        switch ($tipo) {
            case 'data':
                $valor = implode('-', array_reverse(explode('/', $valor)));
                break;

            case 'video':
                // Chama a função do helper
                $retorno = verificar_video($valor);
                $valor = $retorno['link'];
                break;

            case 'video-youtube':
                // Chama a função do helper
                $valor = youtube_video_id($valor);
                break;

            case 'video-vimeo':
                // Chama a função do helper
                $valor = vimeo_video_id($valor);
                break;

            case 'imagem':
                $valor = $this->codemin->imagem_upload($name, $id, $miniaturas);
                break;

            case 'monetario':
                if (empty($valor)) {
                    $valor = '0.00';
                } else {
                    $valor = str_replace('.', '', $valor);
                    $valor = str_replace(',', '.', $valor);
                    $valor = number_format($valor, 2, '.', '');
                }
                break;
            case 'arquivo':
                $valor = $this->codemin->arquivo_upload($name, $id);
                break;
        }
        return $valor;
    }

    /**
     * converter_do_banco
     * 
     * Converte a string para formato humano
     * quando houver necessidade
     * 
     *
     * @access	public
     * @param	$string, $string
     * @return	$string
     */
    public function converter_do_banco($valor, $tipo) {
        switch ($tipo) {
            case 'data':
                $valor = implode('/', array_reverse(explode('-', $valor)));
                break;

            case 'video':
                // Chama a função do helper
                $valor = video_url($valor);
                break;

            case 'video-youtube':
                // Chama a função do helper
                $valor = youtube_video_url($valor);
                break;

            case 'video-vimeo':
                // Chama a função do helper
                $valor = vimeo_video_url($valor);
                break;
        }
        return $valor;
    }

    /**
     * gerar_url_amigavel
     * 
     * Gera uma url amigável única
     * 
     *
     * @access	public
     * @param	$string, $string, $string
     * @return	$string
     */
    public function gerar_url_amigavel($tabela, $campo, $variavel, $id = null) {

        $variavel = url_amigavel($variavel);

        $aux = 1;
        $disponivel = false;
        $newvariavel = $variavel;

        $this->db->where('url_' . $campo, $variavel);
        if ($id)
            $this->db->where('id !=', $id);

        if ($this->db->get($tabela)->num_rows() <= 0) {

            return $variavel;
        } else {

            $aux = 1;
            $found = false;
            while ($found == false) {
                $newvariavel = $variavel . '-' . $aux;
                $this->db->where('url_' . $campo, $newvariavel);
                if ($id)
                    $this->db->where('id !=', $id);

                if ($this->db->get($tabela)->num_rows() <= 0) {
                    $found = true;
                } else {
                    $aux++;
                }
            }

            return $newvariavel;
        }
    }

    /**
     * ordenar_registros
     * 
     * Grava a ação do usuário no banco de dados
     * 
     *
     * @access	public
     * @param	int, int
     * @return null
     */
    public function ordenar_registros($tabela, $registros) {

        $return = array();

        if (!$this->session->userdata('administrador')) {
            $this->db->select('id');
            $this->db->where('nivel', 3);
            $this->db->where('area', $tabela);
            $this->db->where('id_usuario', $this->session->userdata('id'));
            if ($this->db->get('codemin_usuarios_permissoes')->num_rows() <= 0) {
                $return['erro'] = true;
                return $return;
            }
        }


        $aux = 1;
        foreach ($registros as $registro) {
            if ($registro) {

                $explode = explode('registro_', $registro);
                $id = $explode[1];

                $update = array('ordenar' => $aux);
                $this->db->where('id', $id);
                if (!$this->db->update($tabela, $update)) {
                    $erro = true;
                }

                $aux++;
            }
        }
        if (isset($erro)) {
            $return['erro'] = true;
        } else {
            $return['sucesso'] = true;
        }
        return $return;
    }

    /**
     * log_acoes
     * 
     * Grava a ação do usuário no banco de dados
     * 
     *
     * @access	private
     * @param	int, int
     * @return null
     */
    private function log_acoes($id, $acao) {

        $insert = array(
            'id_usuario' => $this->session->userdata('id'),
            'tabela' => $this->uri->segment(2),
            'id_registro' => $id,
            'acao' => $acao,
            'data' => date('Y-m-d H:i:s')
        );
        $this->db->insert('codemin_log_acoes', $insert);
    }

    /**
     * log_acessos
     * 
     * Grava os acessos do usuário no banco de dados
     * 
     *
     * @access	private
     * @param	int, int
     * @return null
     */
    private function log_acessos() {

        $insert = array(
            'id_usuario' => $this->session->userdata('id'),
            'ip_usuario' => $this->session->userdata('ip_address'),
            'user_agent' => $this->session->userdata('user_agent'),
            'data' => date('Y-m-d H:i:s')
        );
        $this->db->insert('codemin_log_acessos', $insert);
    }

    /**
     * pegar_log_acoes
     * 
     * Grava a ação do usuário no banco de dados
     * 
     *
     * @access	public
     * @param	int
     * @return null
     */
    public function pegar_log_acoes($limit, $todos = false) {

        if ($todos & !$this->session->userdata('administrador')) {
            return null;
        }

        $this->db->select('codemin_log_acoes.id_registro, codemin_log_acoes.acao, codemin_log_acoes.tabela, codemin_log_acoes.data');

        if ($todos) {
            $this->db->select('codemin_usuarios.id, codemin_usuarios.nome');
            $this->db->join('codemin_usuarios', 'codemin_usuarios.id = codemin_log_acoes.id_usuario');
        } else {
            $this->db->where('id_usuario', $this->session->userdata('id'));
        }

        $this->db->limit($limit);
        $this->db->order_by('data', 'desc');
        return $this->db->get('codemin_log_acoes')->result();
    }

    /**
     * pegar_log_acoes
     * 
     * Grava a ação do usuário no banco de dados
     * 
     *
     * @access	public
     * @param	int
     * @return null
     */
    public function pegar_log_acessos($limit, $todos = false) {

        if ($todos & !$this->session->userdata('administrador')) {
            return null;
        }

        $this->db->select('codemin_log_acessos.ip_usuario, codemin_log_acessos.user_agent, codemin_log_acessos.data');

        if ($todos) {
            $this->db->select('codemin_usuarios.id, codemin_usuarios.nome');
            $this->db->join('codemin_usuarios', 'codemin_usuarios.id = codemin_log_acessos.id_usuario');
        } else {
            $this->db->where('id_usuario', $this->session->userdata('id'));
        }

        $this->db->limit($limit);
        $this->db->order_by('data', 'desc');
        return $this->db->get('codemin_log_acessos')->result();
    }

    /**
     * gravar_opcoes_selecionadas
     * 
     * Limpa todos os registros com a mesma tabela e id
     * e grava novamente os valores no banco
     * 
     *
     * @access	private
     * @param	int
     * @return null
     */
    public function gravar_opcoes_selecionadas($array, $name, $id) {

        $tabela = $this->uri->segment(2);

        $this->db->where('tabela', $tabela);
        $this->db->where('id_registro', $id);
        $this->db->where('campo', $name);
        $this->db->delete('codemin_opcoes_selecionadas');


        if (is_array($array)) {
            foreach ($array as $opcao) {
                $inserir = array(
                    'tabela' => $tabela,
                    'id_registro' => $id,
                    'campo' => $name,
                    'id_opcao' => $opcao
                );
                $this->db->insert('codemin_opcoes_selecionadas', $inserir);
            }
            unset($inserir);
        }
    }

    /**
     * migrate
     * 
     * Faz o migrate das tabelas no banco
     * de acordo com os inputs passados por array
     * 
     *
     * @access	public
     * @param	$array, boolaan
     * @return null
     */
    public function migrate($dados, $config = false, $tabela = null) {

        if (!$tabela) {
            $tabela = $this->uri->segment(2);
        }

        // CRIAR A TABELA
        echo "<div class='alert alert-info'>CRIAR TABELA '" . $this->db->dbprefix($tabela) . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix($tabela) . "` (
			`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`),
		  	`ativo` tinyint(1) NULL,
		  	`ordenar` int(11) NOT NULL,
		  	`id_usuario` int(11) NOT NULL
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8");

        if ($config) {
            $this->db->where('id', 1);
            if ($this->db->get($tabela)->num_rows() <= 0) {
                $this->db->query("INSERT INTO " . $this->db->dbprefix($tabela) . " (`id`) VALUES (NULL)");
            }
        }

        $colunas = $this->db->list_fields($tabela);

        foreach ($dados as $dado) {
            $tipo = $dado['tipo'];
            $campo = $dado['campo'];
            $colunas = array_map('strtolower', $colunas);
            if (!in_array(strtolower($campo), $colunas)) {

                switch ($tipo) {
                    case 'input-text':
                    case 'monetario':
                    case 'imagem':
                    case 'video':
                    case 'video-vimeo':
                    case 'video-youtube':
                    case 'google-maps':
                    case 'arquivo':
                    case 'hidden':
                    case'senha':
                        $query = "ALTER TABLE `" . $this->db->dbprefix($tabela) . "` ADD `$campo` VARCHAR( 255 ) NOT NULL";
                        $texto = "ADICIONAR COLUNA '" . $this->db->dbprefix($tabela) . "' ADD `$campo` VARCHAR( 255 )";
                        break;

                    case 'data':
                        $query = "ALTER TABLE `" . $this->db->dbprefix($tabela) . "` ADD `$campo` DATE NOT NULL";
                        $texto = "ADICIONAR COLUNA '" . $this->db->dbprefix($tabela) . "' ADD `$campo` DATE";
                        break;

                    case 'text-area':
                    case 'text-area-rich':
                    case 'text-area-rich-simple':
                    case 'text-area-rich-full':
                        $query = "ALTER TABLE `" . $this->db->dbprefix($tabela) . "` ADD `$campo` LONGTEXT NOT NULL";
                        $texto = "ADICIONAR COLUNA '" . $this->db->dbprefix($tabela) . "' ADD `$campo` LONGTEXT";
                        break;

                    case 'select':
                    case 'select-dinamico':
                    case 'radio':
                    case 'radio-dinamico':
                        $query = "ALTER TABLE `" . $this->db->dbprefix($tabela) . "` ADD `$campo` INT( 11 ) NOT NULL";
                        $texto = "ADICIONAR COLUNA '" . $this->db->dbprefix($tabela) . "' ADD `$campo` INT( 11 )";
                        break;

                    case 'videos':
                    case 'imagens':
                    case 'checkbox':
                    case 'checkbox-dinamico':
                    case 'select_cidade':
                        $query = "ALTER TABLE `" . $this->db->dbprefix($tabela) . "` ADD `$campo` TINYINT( 1 ) NULL DEFAULT NULL";
                        $texto = "ADICIONAR COLUNA '" . $this->db->dbprefix($tabela) . "' ADD `$campo` TINYINT( 1 ) NULL DEFAULT NULL";
                }

                $this->db->query($query);

                echo "<div class='alert alert-info'>$texto</div>";

                if (isset($dado['url_amigavel']) & !in_array('url_' . $campo, $colunas)) {
                    $query = "ALTER TABLE `" . $this->db->dbprefix($tabela) . "` ADD `url_$campo` VARCHAR( 255 ) NOT NULL";
                    $texto = "ADICIONAR COLUNA '" . $this->db->dbprefix($tabela) . "' ADD `url_$campo` VARCHAR( 255 )";

                    $this->db->query($query);
                    echo "<div class='alert alert-info'>$texto</div>";
                }
            }
        }
    }

    /**
     *
     * instalar
     * 
     * Cria as tabelas iniciais e adicionar o primeiro usuário administrador
     * 
     *
     * @access	public
     * @param	null
     * @return	null
     */
    public function instalar() {

        // CRIAR A TABELA DE LOG DE ACESSOS (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_log_acessos') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_log_acessos') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_usuario` int(11) NOT NULL,
		  `ip_usuario` varchar(15) NOT NULL,
		  `user_agent` varchar(255) NOT NULL,
		  `data` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE LOG DE AÇÕES (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_log_acoes') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_log_acoes') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_usuario` int(11) NOT NULL,
		  `tabela` varchar(255) NOT NULL,
		  `id_registro` int(11) NOT NULL,
		  `acao` int(1) NOT NULL,
		  `data` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE OPÇÕES (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_opcoes') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_opcoes') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tabela` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `opcao` varchar(255) NOT NULL,
		  `url_opcao` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE OPÇÕES SELECIONADAS (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_opcoes_selecionadas') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_opcoes_selecionadas') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tabela` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `id_registro` int(11) NOT NULL,
		  `id_opcao` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE UPLOADS (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_uploads') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_uploads') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tipo` varchar(255) NOT NULL,
		  `tabela` varchar(255) NOT NULL,
		  `registro` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `arquivo` varchar(255) NOT NULL,
		  `legenda` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE USUÁRIOS PERMISSÕES (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_video') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_video') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tabela` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `registro` int(11) NOT NULL,
		  `video` varchar(255) NOT NULL,
		  `tipo` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE USUÁRIOS (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_usuarios') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_usuarios') . "` (
		  `id` int(1) NOT NULL AUTO_INCREMENT,
		  `ativo` tinyint(1) NOT NULL,
		  `administrador` tinyint(1) NOT NULL,
		  `nome` varchar(255) NOT NULL,
		  `login` varchar(255) NOT NULL,
		  `senha` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE USUÁRIOS PERMISSÕES (PADRÃO)
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_usuarios_permissoes') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_usuarios_permissoes') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_usuario` int(11) NOT NULL,
		  `nivel` int(1) NOT NULL,
		  `area` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE VÍDEOS
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_videos') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_videos') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tabela` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `registro` int(11) NOT NULL,
		  `video` varchar(255) NOT NULL,
		  `tipo` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE CONTATO
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_contatos') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_contatos') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `nome` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `telefone` varchar(255) NOT NULL,
		  `mensagem` text NOT NULL,
		  `data_hora` datetime NOT NULL,
		  `visualizado` TINYINT( 1 ) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE CATALOGO PEDIDOS
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('catalogo_pedidos') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('catalogo_pedidos') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `telefone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `observacoes` text COLLATE utf8_unicode_ci NOT NULL,
		  `total` double(10,2) NOT NULL,
		  `data_hora` datetime NOT NULL,
		  `visualizado` TINYINT( 1 ) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE CATALOGO PEDIDOS PRODUTOS
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('catalogo_pedidos_produtos') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('catalogo_pedidos_produtos') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_pedido` int(11) NOT NULL,
		  `id_produto` int(11) NOT NULL,
		  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `quantidade` int(5) NOT NULL,
		  `preco` decimal(10,2) NOT NULL,
		  `subtotal` decimal(10,2) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // CRIAR A TABELA DE CATALOGO PEDIDOS PRODUTOS
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('codemin_newsletter') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('codemin_newsletter') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `data_hora` datetime NOT NULL,
		  `exportado` tinyint(1) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
        
        // CRIAR TABELA DE CONFIGURAÇÕES DE IMAGENS 
        echo "<div class='alert alert-success'>CRIAR TABELA '" . $this->db->dbprefix('images_config') . "'</div>";
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('images_config') . "` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `largura` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `altura` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `json_pages` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        // Notícias
        $dados = array();
        $dados[] = array('campo' => 'idioma', 'tipo' => 'radio');
        $dados[] = array('campo' => 'titulo', 'url_amigavel' => true, 'tipo' => 'input-text');
        $dados[] = array('campo' => 'keywords', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'description', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'data', 'tipo' => 'data');
        $dados[] = array('campo' => 'chamada', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'texto', 'tipo' => 'text-area-rich');
        $dados[] = array('campo' => 'imagens', 'tipo' => 'imagens');
        $dados[] = array('campo' => 'videos', 'tipo' => 'videos');
        $dados[] = array('campo' => 'capa', 'tipo' => 'imagem');
        $this->migrate($dados, null, 'noticias');

        // Galerias de Imagens
        $dados = array();
        $dados[] = array('campo' => 'idioma', 'tipo' => 'radio');
        $dados[] = array('campo' => 'nome', 'url_amigavel' => true, 'tipo' => 'input-text');
        $dados[] = array('campo' => 'keywords', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'description', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'chamada', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'texto', 'tipo' => 'text-area-rich');
        $dados[] = array('campo' => 'imagens', 'tipo' => 'imagens');
        $dados[] = array('campo' => 'capa', 'tipo' => 'imagem');
        $this->migrate($dados, null, 'galerias_fotos');

        // Banners
        $dados = array();
        $dados[] = array('campo' => 'idioma', 'tipo' => 'radio');
        $dados[] = array('campo' => 'nome', 'url_amigavel' => true, 'tipo' => 'input-text');
        $dados[] = array('campo' => 'link', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'data_inicial', 'tipo' => 'data');
        $dados[] = array('campo' => 'data_final', 'tipo' => 'data');
        $dados[] = array('campo' => 'capa', 'tipo' => 'imagem');
        $this->migrate($dados, null, 'banners');

        // Conteudo
        $dados = array();
        $dados[] = array('campo' => 'idioma', 'tipo' => 'radio');
        $dados[] = array('campo' => 'titulo', 'url_amigavel' => true, 'tipo' => 'input-text');
        $dados[] = array('campo' => 'keywords', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'description', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'texto', 'tipo' => 'text-area-rich');
        $dados[] = array('campo' => 'imagens', 'tipo' => 'imagens');
        $this->migrate($dados, null, 'conteudo');

        // Menu
        $dados = array();
        $dados[] = array('campo' => 'idioma', 'tipo' => 'radio');
        $dados[] = array('campo' => 'titulo', 'url_amigavel' => true, 'tipo' => 'input-text');
        $dados[] = array('campo' => 'menu_pai', 'tipo' => 'select');
        $dados[] = array('campo' => 'funcao', 'tipo' => 'select');
        $dados[] = array('campo' => 'link', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'link_conteudo', 'tipo' => 'checkbox');
        $dados[] = array('campo' => 'link_gercont', 'tipo' => 'checkbox');
        $dados[] = array('campo' => 'abrir', 'tipo' => 'radio');
        $this->migrate($dados, null, 'menu');

        // Contato
        $dados = array();
        $dados[] = array('campo' => 'idioma', 'tipo' => 'radio');
        $dados[] = array('campo' => 'emails', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'titulo', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'texto', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'facebook', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'twitter', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'localizacao', 'tipo' => 'google-maps');
        $dados[] = array('campo' => 'endereco', 'tipo' => 'text-area');
        $this->migrate($dados, null, 'contato');

        // Configurações
        $dados = array();
        $dados[] = array('campo' => 'idioma', 'tipo' => 'radio');
        $dados[] = array('campo' => 'titulo', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'analytics', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'texto_rodape', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'publicador', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'publicador_link', 'tipo' => 'input-text');
        $this->migrate($dados, null, 'configuracoes');

        // Catálogo Categorias
        $dados = array();
        $dados[] = array('campo' => 'nome', 'url_amigavel' => true, 'tipo' => 'input-text');
        $dados[] = array('campo' => 'capa', 'tipo' => 'imagem');
        $this->migrate($dados, null, 'catalogo_categorias');

        // Catálogo Produtos
        $dados = array();
        $dados[] = array('campo' => 'nome', 'url_amigavel' => true, 'tipo' => 'input-text');
        $dados[] = array('campo' => 'capa', 'tipo' => 'imagem');
        $dados[] = array('campo' => 'keywords', 'tipo' => 'input-text');
        $dados[] = array('campo' => 'description', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'imagens', 'tipo' => 'imagens');
        $dados[] = array('campo' => 'descritivo', 'tipo' => 'text-area');
        $dados[] = array('campo' => 'descricao', 'tipo' => 'text-area-rich');
        $dados[] = array('campo' => 'categoria', 'tipo' => 'checkbox');
        $dados[] = array('campo' => 'valor', 'tipo' => 'monetario');
        $this->migrate($dados, null, 'catalogo_produtos');

        // Catálogo Configurações
        $dados = array();
        $dados[] = array('campo' => 'email', 'tipo' => 'input-text');
        $this->migrate($dados, true, 'catalogo_configuracao');

        $nome = $this->input->post('nome');
        $login = $this->input->post('login');
        $senha = $this->input->post('senha');
        if ($nome & $login & $senha) {
            $senha_hash = senha_usuario($this->input->post('senha'));
            $inserir = array(
                'ativo' => 1,
                'administrador' => 1,
                'nome' => $nome,
                'login' => $login,
                'senha' => $senha_hash
            );
            $this->db->insert('codemin_usuarios', $inserir);
            echo "<div class='alert alert-success'>INSERIDO USUÁRIO '$login' COM A SENHA '$senha' NA TABELA '" . $this->db->dbprefix('codemin_usuarios') . "'</div>";
        }
    }

}