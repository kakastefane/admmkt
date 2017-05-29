<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Codemin {

    private $configuracoes = array();
    private $gerenciador = array();

    public function __construct($configuracoes) {

        // $this->output->enable_profiler(TRUE);

        $this->load->model('codemin_model');

        $logado = $this->codemin_model->verificar_login();
        $area = $this->uri->segment(2);

        // Verifica se o usuário está logado
        if (!$logado & $area != 'login') {
            $this->session->set_flashdata('erro', 'Você precisa estar logado para acessar o sistema!');
            redirect(base_url() . $this->uri->segment(1) . '/login', 'location');
            $this->session->sess_destroy();
        } elseif ($logado & $area == 'login') {
            $this->session->set_flashdata('sucesso', 'Você já está logado!');
            redirect(base_url() . $this->uri->segment(1), 'location');
        }

        /*
         *  Verifica se o usuário tem permissão para
         * acessar a área e qual o nível de permissão
         */
        if ($logado) {
            $nivel = $this->codemin_model->verificar_permissao();
            if ($nivel == 0 & $area != 'logout' & $area != null) {
                // Redireciona para a home
                $this->session->set_flashdata('erro', 'Você não tem acesso para acessar essa área!');
                redirect(base_url() . $this->uri->segment(1), 'location');
            } else {
                $configuracoes['nivel_acesso'] = $nivel;
            }
        }

        /*
         * Verifica quais áreas o usuário tem ao menos
         * permissão de acesso para exibir na navbar
         *
         */
        $this->db->where('id_usuario', $this->session->userdata('id'));
        $this->db->where('nivel >', 0);
        $permissoes = $this->db->get('codemin_usuarios_permissoes')->result();

        $configuracoes['scriptFooter'] = array();
        $configuracoes['contentBody'] = array();

        $this->configuracoes = $configuracoes;
        return $this;
    }

    /**
     *
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     *
     * @access  public
     * @param   $var
     * @return  mixed
     */
    public function __get($var) {
        return get_instance()->$var;
    }

    /**
     *
     * montar_codemin
     *
     * Faz a montagem das views para o CRUD
     *
     *
     * @access  public
     * @param   $array, $string, $array
     * @return  null
     */
    public function montar_codemin($dados = null, $config = null) {


        $acao = $this->uri->segment(3);

        $data['navlinks'] = $this->configuracoes['navlinks'];
        $data['permissoes'] = $this->configuracoes['permissoes'];
        $data['areas'] = $this->configuracoes['areas'];
        $data['activelink'] = $this->configuracoes['activelink'];

        $data['nivel_acesso'] = $this->configuracoes['nivel_acesso'];

        $this->load->view('codemin/header_view', $data);
        $this->load->view('codemin/nav_view');

        /*
         *
         *   Cria a tabela com os campos do array $dados.
         *
         *   Ao colocar online comentar o if
         *   e em 'application/config/database.php'
         *   deixar o db_debug setado como false
         *
         *   $db['default']['db_debug'] = TRUE;
         *
         */

        if (!is_null($dados)) {
            if ($acao == 'migrate') {
                $this->codemin_model->migrate($dados);
            }

            switch ($acao) {
                case 'adicionar':
                    $this->adicionar_codemin($dados, $config);
                    break;

                case 'editar':
                    $this->editar_codemin($dados, $config);
                    break;

                case 'ativo_ajax':
                    $this->ativo_ajax_codemin();
                    break;

                case 'excluir':
                    $this->excluir_codemin($dados);
                    break;

                case 'excluir_ajax':
                    $this->excluir_ajax_codemin($dados);
                    break;

                default:
                    $this->listar_codemin($config);
                    break;
            }
        }


        $footer['scriptFooter'] = $this->configuracoes['scriptFooter'];
        $this->load->view('codemin/footer_view', $footer);
    }

    /**
     * montar_codemin_config
     *
     * Faz a montagem da view e cria o update
     *
     *
     * @access  public
     * @param   $array, $string, boolean
     * @return  null
     */
    public function montar_codemin_config($dados, $titulo, $usuario = false) {

        /*
         *
         *   Cria a tabela com os campos do array $dados.
         *
         *   Ao colocar online comentar o if
         *   e em 'application/config/database.php'
         *   deixar o db_debug setado como false
         *
         *   $db['default']['db_debug'] = TRUE;
         *
         */
        if ($this->uri->segment(3) == 'migrate') {
            $this->codemin_model->migrate($dados, true);
        }

        $data['navlinks'] = $this->configuracoes['navlinks'];
        $data['permissoes'] = $this->configuracoes['permissoes'];
        $data['areas'] = $this->configuracoes['areas'];
        $data['activelink'] = $this->configuracoes['activelink'];
        $data['nivel_acesso'] = $this->configuracoes['nivel_acesso'];

        $this->load->view('codemin/header_view', $data);
        $this->load->view('codemin/nav_view');

        $this->editar_codemin($dados, $titulo, $usuario);

        $footer['scriptFooter'] = $this->configuracoes['scriptFooter'];
        $this->load->view('codemin/footer_view', $footer);
    }

    /**
     * montar_codemin_usuarios
     *
     * Faz a montagem das views para o usuário,
     * busca as permissões no banco de dados e
     * cria os radios box para cada área.
     *
     *
     * @access  public
     * @param   $string
     * @return  null
     */
    public function montar_codemin_usuarios($titulo) {

        $data['navlinks'] = $this->configuracoes['navlinks'];
        $data['permissoes'] = $this->configuracoes['permissoes'];
        $data['areas'] = $this->configuracoes['areas'];
        $data['activelink'] = $this->configuracoes['activelink'];

        $this->configuracoes['navlinks']['usuarios'] = "Usuários";

        $data['contentBody'] = $this->configuracoes['contentBody'];
        $data['nivel_acesso'] = $this->configuracoes['nivel_acesso'];

        $this->load->view('codemin/header_view', $data);
        $this->load->view('codemin/nav_view');

        // Dados dos usuários
        $dados[] = array(// Nome do usuário
            'titulo' => 'Nome',
            'campo' => 'nome',
            'tipo' => 'input-text',
            'placeholder' => 'Nome Completo'
        );
        $dados[] = array(// Login
            'titulo' => 'Login',
            'campo' => 'login',
            'tipo' => 'input-text',
            'placeholder' => 'Login do usuário'
        );
        $dados[] = array(// Nome do usuário
            'titulo' => 'Senha',
            'campo' => 'senha',
            'tipo' => 'senha',
            'placeholder' => 'Senha do usuário'
        );
        $array = array(0 => 'Não', 1 => 'Sim');
        $dados[] = array(// Nome do usuário
            'titulo' => 'Administrador',
            'campo' => 'administrador',
            'tipo' => 'select',
            'dados' => $array,
            'placeholder' => 'Senha do usuário'
        );

        $data['titulo'] = $titulo;

        $tabela = 'codemin_usuarios';
        $acao = $this->uri->segment(3);
        $id = $this->uri->segment(4);

        $this->load->library('form_validation');

        switch ($acao) {

            case 'adicionar':

                $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[6]|max_length[255]');
                $this->form_validation->set_rules('login', 'Login', 'required|is_unique[codemin_usuarios.login]|min_length[4]|max_length[20]');
                $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]|max_length[255]');


                if ($_POST & $this->form_validation->run() == TRUE) {

                    $insert = array(
                        'nome' => $this->input->post('nome'),
                        'login' => $this->input->post('login'),
                        'senha' => senha_usuario($this->input->post('senha')),
                        'administrador' => $this->input->post('administrador'),
                        'ativo' => $this->input->post('ativo')
                    );
                    if ($this->db->insert($tabela, $insert)) {
                        $this->session->set_flashdata('sucesso', 'Registro adicionado com sucesso!');
                        redirect('/gercont/usuarios/', 'location');
                    } else {
                        $this->session->set_flashdata('erro', 'Houve um erro ao adicionar o registro!');
                    }
                }

                // Inicia a montagem do formulário
                $data['dados'] = $this->montar_campos($dados);
                $data['contentBody'] = $this->configuracoes['contentBody'];
                $this->load->view('codemin/adicionar_view', $data);

                break;

            case 'editar':

                $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[6]|max_length[255]');
                $this->form_validation->set_rules('login', 'Login', 'required|min_length[4]|max_length[20]');
                $this->form_validation->set_rules('senha', 'Senha', 'min_length[6]|max_length[255]');

                if ($_POST & $this->form_validation->run() == TRUE) {

                    $update = array(
                        'nome' => $this->input->post('nome'),
                        'login' => $this->input->post('login'),
                        'administrador' => $this->input->post('administrador'),
                        'ativo' => $this->input->post('ativo')
                    );
                    if ($this->input->post('senha')) {
                        $update['senha'] = senha_usuario($this->input->post('senha'));
                    }
                    $this->db->where('id', $id);
                    if ($this->db->update($tabela, $update)) {

                        // Permissões do usuário
                        $this->db->where('id_usuario', $id);
                        $this->db->delete($tabela . '_permissoes');
                        foreach ($this->configuracoes['areas'] as $key => $value) {
                            $insert = array(
                                'area' => $key,
                                'nivel' => $this->input->post('usuario-' . $key),
                                'id_usuario' => $id
                            );
                            $this->db->insert($tabela . '_permissoes', $insert);
                        }

                        $this->session->set_flashdata('sucesso', 'Registro editado com sucesso!');
                        redirect('/gercont/usuarios/', 'location');
                    } else {
                        $this->session->set_flashdata('erro', 'Houve um erro ao adicionar o registro!');
                    }
                }

                // Pega os registros do banco
                $this->db->where('id', $id);
                $this->db->where($tabela . '.ativo !=', 2); // Pseudo excluído
                $resultado = $this->db->get($tabela)->row_array();

                // Pega as permissoes
                $this->db->where('id_usuario', $id);
                $permissoes = array();
                foreach ($this->db->get($tabela . '_permissoes')->result() as $permissao) {
                    $permissoes[$permissao->area] = $permissao->nivel;
                }

                // Inicia a montagem do formulário
                $data['dados'] = $this->montar_campos($dados, $resultado, $permissoes);
                $data['contentBody'] = $this->configuracoes['contentBody'];
                $this->load->view('codemin/editar_view', $data);

                break;

            case 'excluir':

                $id = $this->uri->segment(4);

                $this->db->where('id', $id);
                $this->db->update($tabela, array('ativo' => 2));

                redirect('/gercont/usuarios', 'location');

                break;

            case 'excluir_ajax':

                $id = $this->uri->segment(4);

                $this->db->where('id', $id);
                $resultado = array("sucesso" => $this->db->update($tabela, array('ativo' => 2)));
                echo json_encode($resultado);
                exit;

            default:

                $data['listagens'] = array('nome', 'login');
                $data['titulo'] = $titulo;

                // Pega todos os resultados
                $this->db->order_by('id', 'desc');
                $this->db->where($tabela . '.ativo !=', 2); // Pseudo excluído
                $data['resultados'] = $this->db->get($tabela)->result_array();

                // Monta a view com a listagem
                $this->load->view('codemin/listagem_view', $data);

                break;
        }

        $footer['scriptFooter'] = $this->configuracoes['scriptFooter'];
        $this->load->view('codemin/footer_view', $footer);
    }

    /**
     *
     * adicionar_codemin
     *
     * Exbir o formulário para adicionar e insere se tiver POST
     *
     *
     * @access  private
     * @param   $array, $string
     * @return  null
     */
    private function adicionar_codemin($dados, $config) {

        $titulo = $config['titulo'];

        $tabela = $this->uri->segment(2);
        $nivel_acesso = $this->configuracoes['nivel_acesso'];

        $resultado = $this->codemin_model->adicionar($dados, $nivel_acesso);

        if ($resultado) {
            $this->session->set_flashdata('sucesso', 'Registro adicionado com sucesso!');
            redirect('/gercont/' . $tabela, 'location');
        } elseif ($resultado === FALSE) {
            $this->session->set_flashdata('erro', 'Houve um erro ao adicionar o Registro!');
        }

        // Inicia a montagem do formulário
        $data['dados'] = $this->montar_campos($dados);
        $data['titulo'] = $titulo;

        $data['contentBody'] = $this->configuracoes['contentBody'];

        $this->load->view('codemin/adicionar_view', $data);
    }

    /**
     *
     * editar_codemin
     *
     * Exbir o formulário para editar e atualiza se tiver POST
     *
     *
     * @access  private
     * @param   $array, $string, boolean
     * @return  null
     */
    private function editar_codemin($dados, $config, $usuario = false) {

        // Pega os dados básicos
        $titulo = $config['titulo'];
        $tabela = $this->uri->segment(2);
        $nivel_acesso = $this->configuracoes['nivel_acesso'];

        // Se tiver um post edita
        $editado = $this->codemin_model->editar($dados, $nivel_acesso, $usuario);
        if ($editado) {
            $this->session->set_flashdata('sucesso', 'Registro editado com sucesso!');
            redirect('/gercont/' . $tabela, 'location');
        } elseif ($editado === false) {
            $this->session->set_flashdata('erro', 'Houve um erro ao editar o registro!');
        }

        // Busca os resultado do item
        $resultado = $this->codemin_model->resultado($nivel_acesso);

        // Se não retornar nenhum item, dá mensagem de erro
        if (!$resultado & !$usuario) {
            $this->session->set_flashdata('erro', 'Esse registro não existe ou você não tem permissão para editar');
            redirect('/gercont/' . $tabela, 'location');
        }

        // Inicia a montagem da tabela
        $data['dados'] = $this->montar_campos($dados, $resultado);
        $data['titulo'] = $titulo;

        $data['contentBody'] = $this->configuracoes['contentBody'];

        $this->load->view('codemin/editar_view', $data);
    }

    /**
     *
     * excluir_codemin
     *
     * Exclui o registro do banco de dados
     *
     *
     * @access  private
     * @param   $array
     * @return  null
     */
    private function excluir_codemin($dados) {

        $nivel_acesso = $this->configuracoes['nivel_acesso'];
        $tabela = $this->uri->segment(2);

        $this->codemin_model->excluir($nivel_acesso);

        $this->session->set_flashdata('sucesso', 'Registro removido com sucesso!');
        redirect('/gercont/' . $tabela, 'location');
    }

    /**
     *
     * excluir_ajax_codemin
     *
     * Exclui o registro do banco de dados por ajax
     *
     *
     * @access  private
     * @param   $array
     * @return  json
     */
    private function excluir_ajax_codemin($dados) {

        $nivel_acesso = $this->configuracoes['nivel_acesso'];

        $resultado = array("sucesso" => $this->codemin_model->excluir($nivel_acesso));

        echo json_encode($resultado);

        exit;
    }

    /**
     *
     * ativo_ajax_codemin
     *
     * Atualiza o status de ativo de um registro por ajax
     *
     *
     * @access  private
     * @param   null
     * @return  json
     */
    private function ativo_ajax_codemin() {

        $nivel_acesso = $this->configuracoes['nivel_acesso'];

        $resultado = $this->codemin_model->status_ativo($nivel_acesso);

        echo json_encode($resultado);

        exit;
    }

    /**
     *
     * listar_codemin
     *
     * Lista todos os registros
     *
     *
     * @access  private
     * @param   $array, $titulo
     * @return  null
     */
    private function listar_codemin($config) {

        $titulo = $config['titulo'];

        $nivel_acesso = $this->configuracoes['nivel_acesso'];

        $return = $this->codemin_model->resultados($config, $nivel_acesso);

        $data['nivel_acesso'] = $nivel_acesso;
        $data['titulo'] = $titulo;
        $data['listagens'] = $return->listagens;
        $data['resultados'] = $return->resultados;
        $data['config'] = $config;

        // Monta a view com a listagem
        $this->load->view('codemin/listagem_view', $data);
    }

    /**
     *
     * montar_campos
     *
     * Monta a estrutura dos campos e chamada a função montar_campo para montar cada um individual
     *
     *
     * @access  private
     * @param   $array, $array
     * @return  $string
     */
    private function montar_campos($campos, $resultado = null, $permissoes = null) {


        // Guarda algumas variáveis
        $nivel_acesso = $this->configuracoes['nivel_acesso'];
        $area = $this->uri->segment(2);
        $acao = ucfirst($this->uri->segment(3, 'Editar'));

        // Abre o formulário
        $data = array(
            'class' => 'form-horizontal area-' . $area,
            'accept-charset' => 'utf8'
        );
        $return = form_open_multipart(null, $data);

        // Para cada campo monta o html básico e insere o campo formatado

        foreach ($campos as $campo) {

            $valor = null;
            if (isset($resultado)) {
                $coluna = $campo['campo'];

                if (isset($resultado[$coluna])) {
                    $valor = $resultado[$coluna];
                }
            }
            $input = $this->montar_campo($campo, $valor);

            $dica = null;
            if (isset($campo['dica'])) {
                $dica = "<span class='help-block'>" . $campo['dica'] . "</span>";
            }

            if ($campo['tipo'] !== 'hidden') {
                $return .= "<div class='control-group campo-" . $campo['campo'] . "'>
                    <label class='control-label'>" . $campo['titulo'] . ":</label>
                    <div class='controls'>
                        $input
                        $dica
                    </div>
                </div>";
            } else {
                $return .= $input;
            }
        }

        if ($permissoes !== null) {
            $return .= '<hr/>';
            foreach ($this->configuracoes['areas'] as $key => $value) {
                $array = array(
                    0 => 'Nenhum',
                    1 => 'Acesso',
                    2 => 'Acesso e Publicar',
                    3 => 'Total'
                );
                $permissao = null;
                if (isset($permissoes[$key]))
                    $permissao = $permissoes[$key];

                $radios = $this->montar_radios($array, 'usuario-' . $key, $permissao);

                $return .= "<div style='margin-bottom: 20px;'>
                    <span>" . $value . ":</span>
                    <div class='radio'>
                        $radios
                    </div>
                </div>";
            }
        }

        if ($nivel_acesso >= 2) {
            $array = array(
                1 => 'Ativo',
                0 => 'Inativo'
            );
            $radios = $this->montar_radios($array, 'ativo', $resultado['ativo']);
            $return .= "
                    <hr/><div style='margin-bottom: 20px;'>
                    <span><b>Ativo:</b></span>
                    <div class='radio'>
                        $radios
                    </div>
                </div>";
        }

        $excluir = null;
        if ($this->uri->segment(3) == 'editar') {
            $excluir = "<a class='btn btn-danger confirmar-excluir' href='" . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/excluir/" . $this->uri->segment(4) . "'>Excluir</a>";
        }
        $return .= "<div class='form-actions'>
            <input type='submit' value='$acao' class='btn btn-success' />
            $excluir
            <a href='" . base_url() . "gercont/" . $this->uri->segment(2) . "' value='$acao' class='btn' />Voltar</a>
        </div>";
        $return .= form_close();

        return $return;
    }

    /**
     *
     * montar_campo
     *
     * Monta cada campo indivídualmente,
     * insere script no rodapé ou código no
     * corpo do formulário quando necessário
     *
     *
     * @access  private
     * @param   $array, $string
     * @return  $string
     */
    private function montar_campo($campo, $valor = null) {

        // Guarda algumas variáveis
        $acao = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $name = $campo['campo'];
        $tipo = $campo['tipo'];
        $titulo = $campo['titulo'];

        $placeholder = null;
        if (isset($campo['placeholder'])) {
            $placeholder = $campo['placeholder'];
        }

        // Se tiver algum registro, faz a conversão do banco
        if ($valor) {
            $valor = $this->codemin_model->converter_do_banco($valor, $tipo);
        }

        switch ($tipo) {

            // Input text comum
            case 'input-text':
            case 'video':
            case 'video-vimeo':
            case 'video-youtube':
                $data = array(
                    'name' => $name,
                    'value' => set_value($name, $valor),
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                );

                return form_input($data);

                break;

            // Gerenciamento de Galeria de Vídeo por Ajax
            case 'videos':

                if ($acao == 'adicionar') {
                    return '<span>Disponível em editar</span>';
                }

                $videos = $this->pegar_videos($name);

                $this->configuracoes['contentBody'][] = '<!-- Modal -->
                <div id="modal-' . $name . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left: 50%; width: 980px; margin-left: -490px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Gerenciar Galeria de Vídeos</h3>
                        </div>
                        <div class="modal-body">
                            <div><input type="text" /><input type="button" value="Adicionar" class="btn btn-success" /></div>
                            <div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Imagem</th>
                                            <th>Link</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>' . $videos . '</tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Salvar e Fechar</button>
                        </div>
                    </div>
                </div>';
                $this->configuracoes['scriptFooter'][] = "<script>
                    $(document).ready(function(){
                        videos('" . base_url() . "opcoes/videos','" . $this->uri->segment(2) . "','" . $name . "','" . $this->uri->segment(4, 1) . "');
                    })
                </script>";
                return '<a href="javascript:void(0);" role="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-' . $name . '">Gerenciar Vídeos</a>';
                break;

            // Input text com máscara para data
            case 'data':
                if (!$valor)
                    $valor = date('d/m/Y');
                $data = array(
                    'name' => $name,
                    'value' => set_value($name, $valor),
                    'placeholder' => $placeholder,
                    'alt' => 'date',
                    'autocomplete' => 'off',
                    'class' => 'form-control'
                );
                return form_input($data);
                break;

            // Input text com máscara para valor monetário
            case 'monetario':
                $data = array(
                    'name' => $name,
                    'value' => set_value($name, $valor),
                    'placeholder' => $placeholder,
                    'alt' => 'decimal',
                    'autocomplete' => 'off',
                    'class' => 'form-control'
                );
                return form_input($data);
                break;

            // Input text com máscara para valor monetário
            case 'senha':
                $data = array(
                    'name' => $name,
                    'placeholder' => $placeholder,
                    'autocomplete' => 'off',
                    'class' => 'form-control'
                );
                return form_password($data);
                break;

            // Textarea simples
            case 'text-area':
                $data = array(
                    'name' => $name,
                    'placeholder' => $placeholder,
                    'value' => set_value($name, $valor),
                    'autocomplete' => 'off',
                    'class' => 'form-control'
                );
                return form_textarea($data);
                break;

            //  Textarea com CKEDITOR
            case 'text-area-rich-simple':
            case 'text-area-rich':
            case 'text-area-rich-full':
                $botao = null;
                if (isset($campo['imagens'])) {
                    $botao = $this->banco_de_imagens();
                }
                $this->configuracoes['scriptFooter'][] = "<script>
                                                                                CKEDITOR.replace( '$name' ,
                                                                                    {
                                                                                        baseHref: '" . base_url() . "',
                                                                                        height : '600',
                                                                                        filebrowserBrowseUrl : '" . base_url() . "public/codemin/ckfinder/ckfinder.html',
                                                                                        filebrowserImageBrowseUrl : '" . base_url() . "public/codemin/ckfinder/ckfinder.html?type=Images',
                                                                                        filebrowserFlashBrowseUrl : '" . base_url() . "public/codemin/ckfinder/ckfinder.html?type=Flash',
                                                                                        filebrowserUploadUrl : '" . base_url() . "public/codemin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                                                        filebrowserImageUploadUrl : '" . base_url() . "public/codemin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                                                                        filebrowserFlashUploadUrl : '" . base_url() . "public/codemin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                                                                                    } );
                                                                            </script>";
                return form_textarea($name, set_value($name, $valor)) . $botao;
                break;

            // Select com opções vindas de array
            case 'select':
                return form_dropdown($name, $campo['dados'], set_value($name, $valor));
                break;
            case 'multiple':
                $tabela = $this->uri->segment(2);
                $acao = $this->uri->segment(3);
                $id = $this->uri->segment(4);
                $array_res = array();

                if ($acao == 'editar') {

                    $this->db->where('id', $id);
                    $query_f = $this->db->get('franqueados');
                    $line_ = $query_f->row();
                    $uf = $line_->uf;

                    $cidades_query = $this->db->get_where('cidades', array('uf' => $uf));
                    $cidades = array(0 => 'Selecione uma cidade');

                    foreach ($cidades_query->result() as $line_city) {
                        $cidades[$line_city->id] = $line_city->nome;
                    }

                    $res_query = $this->db->get_where('codemin_opcoes_selecionadas', array('tabela' => $tabela, 'campo' => $name, 'id_registro' => $id));
                    if ($res_query->num_rows() > 0) {

                        foreach ($res_query->result_array() as $line) {
                            $array_res[] = $line['id_opcao'];
                        }
                    }

                    return form_multiselect($name . '[]', $cidades, $array_res);
                } else {
                    return form_multiselect($name . '[]', $campo['dados'], set_value($name, $valor));
                }
                break;
            case 'select_cidade':
                $tabela = $this->uri->segment(2);
                $acao = $this->uri->segment(3);
                $id = $this->uri->segment(4);
                if ($acao == 'editar') {
                    $uf_selecionada = $this->db->where('id', $id)->get($tabela)->row()->uf;
                    $cidades = $this->codemin->array_select('cidades', 'nome', 'asc', Array('uf', $uf_selecionada), 'nome');
                    return form_dropdown($name, $cidades, set_value($name, $valor));
                } else {
                    return form_dropdown($name, $campo['dados'], set_value($name, $valor));
                }
                break;
            // Select com gerenciador de categorias em AJAX
            case 'select-dinamico':
                $opcoes = $this->pegar_opcoes($name);
                $this->configuracoes['contentBody'][] = $this->modal_opcoes($name, $opcoes, $titulo);
                $this->configuracoes['scriptFooter'][] = "<script>
                    $(document).ready(function(){
                        opcoes('" . base_url() . "opcoes/','$name','" . $this->uri->segment(2) . "','select');
                    })
                </script>";
                return form_dropdown($name, $opcoes, set_value($name, $valor)) .
                        ' <a href="#modal-' . $name . '" role="button" class="btn btn-primary btn-mini" data-toggle="modal">Gerenciar</a>';
                ;
                break;

            // Select com opções vindas de array
            case 'google-maps':
                if (!isset($valor) || $valor == '') {
                    $valor = "0, 0";
                }
                $this->configuracoes['scriptFooter']['google-maps'] = '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>';
                $this->configuracoes['scriptFooter'][] = "<script>
                    $(document).ready(function(){
                        google_maps('$name',$valor);
                    })
                </script>";

                $hidden_maps = array(
                    'type' => 'hidden',
                    'name' => $name,
                    'value' => set_value($name, $valor),
                    'id' => "input-google-maps-$name"
                );
                return "<style>#google-maps-$name img { max-width: none; }</style>
                        " . form_input($hidden_maps) . "
                        " . form_input(array('type' => 'input-tet', 'id' => 'pac-input', 'class' => 'controls_maps')) . "
                        <div id='map-canvas' class='google-maps'></div>";
                break;
            //style='width: 100%; height: 400px;'
            // Radios
            case 'radio':
                return $this->montar_radios($campo['dados'], $name, $valor);
                break;

            // Select com gerenciador de categorias em AJAX
            case 'radio-dinamico':
                $opcoes = $this->pegar_opcoes($name);
                $this->configuracoes['contentBody'][] = $this->modal_opcoes($name, $opcoes, $titulo);
                $this->configuracoes['scriptFooter'][] = "<script>
                    $(document).ready(function(){
                        opcoes('" . base_url() . "opcoes/','$name','" . $this->uri->segment(2) . "','radio');
                    })
                </script>";
                return '<span id="label_' . $campo['campo'] . '">' . $this->montar_radios($opcoes, $name, $valor) . '</span>' .
                        ' <a href="#modal-' . $name . '" role="button" class="btn btn-primary btn-mini" data-toggle="modal">Gerenciar</a>';
                break;
            // Checkbox
            case 'checkbox':
                return $this->montar_checkbox($campo['dados'], $name);
                break;
            case 'checkbox-unico':
                return $this->montar_checkbox_unico($titulo, $name, $valor);
                break;
            // Select com gerenciador de categorias em AJAX
            case 'checkbox-dinamico':
                $opcoes = $this->pegar_opcoes($name);
                $this->configuracoes['contentBody'][] = $this->modal_opcoes($name, $opcoes, $titulo);
                $this->configuracoes['scriptFooter'][] = "<script>
                    $(document).ready(function(){
                        opcoes('" . base_url() . "opcoes/','$name','" . $this->uri->segment(2) . "','checkbox');
                    })
                </script>";
                return '<span id="label_' . $campo['campo'] . '">' . $this->montar_checkbox($opcoes, $name) . '</span>' .
                        ' <a href="#modal-' . $name . '" role="button" class="btn btn-primary btn-mini" data-toggle="modal">Gerenciar</a>';
                ;
                break;

            // Upload de imagem no submit (sem ajax)
            case 'imagem':
                $imagem = null;
                if ($valor) {
                    $imagem = "<img src='" . base_url() . "uploads/" . $this->uri->segment(2) . "/" . $this->uri->segment(4, 1) . "/mini_thumbs/" . $valor . "' /> ";
                }
                return $imagem . form_upload($name);
                break;

            // Upload de imagens por ajax usando o Jquery Upload
            case 'imagens':

                if ($acao == 'adicionar') {
                    return '<span>Disponível em editar</span>';
                }

                $this->configuracoes['contentBody'][] = '<!-- Modal -->
                <div id="modal-' . $name . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left: 50%; width: 980px; margin-left: -490px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Gerenciar Imagens para ' . $titulo . '</h3>
                        </div>
                        <div class="modal-body">
                            <form id="upload-' . $name . '" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
                                <div class="row fileupload-buttonbar">
                                    <div class="span7">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus icon-white"></i>
                                            <span>Adicionar...</span>
                                            <input type="file" name="files[]" multiple>
                                        </span>
                                        <button type="submit" class="btn btn-primary start">
                                            <i class="icon-upload icon-white"></i>
                                            <span>Iniciar upload</span>
                                        </button>
                                        <button type="reset" class="btn btn-warning cancel">
                                            <i class="icon-ban-circle icon-white"></i>
                                            <span>Cancelar upload</span>
                                        </button>
                                        <button type="button" class="btn btn-danger delete">
                                            <i class="icon-trash icon-white"></i>
                                            <span>Deletar</span>
                                        </button>
                                        <input type="checkbox" class="toggle">
                                        <span class="fileupload-loading"></span>
                                    </div>
                                    <div class="span5 fileupload-progress fade">
                                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bar" style="width:0%;"></div>
                                        </div>
                                        <div class="progress-extended">&nbsp;</div>
                                    </div>
                                </div>
                                <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Salvar e Fechar</button>
                        </div>
                    </div>
                </div>';
                $this->configuracoes['scriptFooter'][] = "<script>
                    $(document).ready(function(){
                        uploader('#upload-$name','" . base_url() . "uploader/index/imagem/" . $this->uri->segment(2) . "/" . $this->uri->segment(4, 1) . "/" . $name . "');
                    })
                </script>";
                return '<a href="javascript:void(0);" role="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-' . $name . '">Gerenciar Imagens</a>';
                break;
            case 'arquivo':
                return form_upload($name);
                break;
            case 'hidden':
                $hidden = array(
                    'type' => 'hidden',
                    'name' => $name,
                    'value' => set_value($name, $valor)
                );
                return form_input($hidden);
                break;
        }
    }

    /**
     *
     * banco_de_imagens
     *
     * Cria um modal, a chamada para função
     * javacrip e retorna o botão para abrir
     * o modal do Banco de imagens
     *
     *
     * @access  private
     * @param   null
     * @return  $string
     */
    private function banco_de_imagens() {
        $this->configuracoes['contentBody']['banco-de-imagens'] = '<!-- Modal -->
        <div id="modal-banco-de-imagens" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left: 50%; width: 980px; margin-left: -490px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Gerenciar Banco de Imagens</h3>
                </div>
                <div class="modal-body">
                    <form id="upload-banco-de-imagens" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
                        <div class="row fileupload-buttonbar">
                            <div class="span7">
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus icon-white"></i>
                                    <span>Adicionar...</span>
                                    <input type="file" name="files[]" multiple>
                                </span>
                                <button type="submit" class="btn btn-primary start">
                                    <i class="icon-upload icon-white"></i>
                                    <span>Iniciar upload</span>
                                </button>
                                <button type="reset" class="btn btn-warning cancel">
                                    <i class="icon-ban-circle icon-white"></i>
                                    <span>Cancelar upload</span>
                                </button>
                                <button type="button" class="btn btn-danger delete">
                                    <i class="icon-trash icon-white"></i>
                                    <span>Deletar</span>
                                </button>
                                <input type="checkbox" class="toggle">
                                <span class="fileupload-loading"></span>
                            </div>
                            <div class="span5 fileupload-progress fade">
                                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="bar" style="width:0%;"></div>
                                </div>
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Salvar e Fechar</button>
                </div>
            </div>
        </div>';
        $this->configuracoes['scriptFooter']['banco-de-imagens'] = "<script>
            $(document).ready(function(){
                uploader('#upload-banco-de-imagens','" . base_url() . "uploader/index/imagem/banco_imagens/1/imagens');
            })
        </script>";
        return '<br/><a href="#modal-banco-de-imagens" role="button" class="btn btn-primary" data-toggle="modal">Gerenciar Galeria de Imagens</a>';
    }

    /**
     *
     * array_select
     *
     * Cria um modal com um pequeno formulário
     * e um tabela abaixo com as opções gerenciadas
     * por ajax. Pode ser usada para select, checkbox
     * ou radio.
     *
     *
     * @access  private
     * @param   $string, $string
     * @return  $array
     */
    public function array_select($tabela, $campo, $order = 'asc', $where = false, $group_by = false, $agrupador = 'id') {

        $return = array();
        if ($where) {
            $this->db->where($where[0], $where[1]);
        }
        if ($group_by) {
            $this->db->group_by($group_by);
        }
        $this->db->where('ativo', 1);
        $this->db->order_by($campo, $order);
        foreach ($this->db->get($tabela)->result_array() as $item) {
            $return[$item[$agrupador]] = $item[$campo];
        }
        return $return;
    }

    /**
     *
     * modal_opcoes
     *
     * Cria um modal com um pequeno formulário
     * e um tabela abaixo com as opções gerenciadas
     * por ajax. Pode ser usada para select, checkbox
     * ou radio.
     *
     *
     * @access  private
     * @param   $string, $array, $string
     * @return  $string
     */
    private function modal_opcoes($name, $opcoes, $titulo) {

        $opcoes_linhas = null;
        if (isset($opcoes)) {
            foreach ($opcoes as $key => $value) {
                $opcoes_linhas .= "<tr><td>$value</td><td><input type='button' class='btn btn-danger' value='Remover'><input type='hidden' value='$key'/></tr>";
            }
        }

        return '<!-- Modal -->
        <div id="modal-' . $name . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Gerenciar Opções para ' . $titulo . '</h3>
                </div>
                <div class="modal-body">
                    <div class="controls-row">
                        <input type="text" placeholder="Digite uma opção e clique em Adicionar" class="span5" /><input type="button" value="Adicionar" class="btn btn-success span2" />
                    </div>
                    <table class="table">
                        <thead>
                            <th>Nome</th>
                            <th>Ações</th>
                        </thead>
                        <tbody>
                            ' . $opcoes_linhas . '
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Salvar e Fechar</button>
                </div>
            </div>
        </div>';
    }

    private function montar_radios($valores, $name, $selecionado) {

        $return = null;
        foreach ($valores as $key => $value) {

            $checado = null;
            if ($key == $selecionado) {
                $checado = "checked='checked'";
            }

            $return .= "<label style='margin-right:20px;'>
                    <input type='radio' name='$name' value='$key' $checado>
                    $value
                </label>";
        }

        return $return;
    }

    private function montar_checkbox_unico($titulo, $name, $valor = 0) {
        $checado = "";
        if ($valor == 1) {
            $checado = "checked='checked'";
        }
        return "<label style='margin-right:10px;'>
                    <input type='checkbox' name='$name' $checado>
                    $titulo
                </label>";
    }

    private function montar_checkbox($valores, $name) {

        $this->db->where('tabela', $this->uri->segment(2));
        $this->db->where('campo',$name);
        $this->db->where('id_registro', $this->uri->segment(4));
        $this->db->select('id_opcao');
        $marcados = array();
        foreach ($this->db->get('codemin_opcoes_selecionadas')->result() as $marcado) {
            $marcados[] = $marcado->id_opcao;
        }

        $return = null;
        foreach ($valores as $key => $value) {

            $checado = null;
            if (in_array($key, $marcados)) {
                $checado = "checked='checked'";
            }

            $return .= "<label style='margin-right:10px;'>
                    <input type='checkbox' name='$name" . "[]' value='$key' $checado>
                    $value
                </label>";
        }

        return $return;
    }

    /**
     * pegar_opcoes
     *
     * Pega as opções do banco de dados e grava em uma array
     *
     *
     * @access  private
     * @param   $string
     * @return  $array
     */
    public function pegar_opcoes($name) {

        $tabela = $this->uri->segment(2);

        $this->db->where('tabela', $tabela);
        $this->db->where('campo', $name);
        $opcoes = array();
        foreach ($this->db->get('codemin_opcoes')->result() as $opcao) {
            $opcoes[$opcao->id] = $opcao->opcao;
        }
        return $opcoes;
    }

    /**
     * pegar_videos
     *
     * Pegar
     *
     *
     * @access  private
     * @param   $string
     * @return  $array
     */
    public function pegar_videos($campo) {

        $videos = '';
        $this->db->where('registro', $this->uri->segment(4, 1));
        $this->db->where('campo', $campo);
        $this->db->where('tabela', $this->uri->segment(2));
        foreach ($this->db->get('codemin_videos')->result() as $video) {
            switch ($video->tipo) {
                case 'youtube':
                    $videos .= "<tr>
                        <td><img src='http://img.youtube.com/vi/$video->video/1.jpg' /></td>
                        <td><a target='_blank' href='http://youtu.be/$video->video'>http://youtu.be/$video->video</a></td>
                        <td><button class='btn btn-danger'>Excluir<input type='hidden' value='$video->id'></button></td>
                    </tr>";
                    break;

                case 'vimeo':
                    $img = miniatura_video('http://vimeo.com/' . $video->video);
                    $videos .= "<tr>
                        <td><img src='$img[thumb]' /></td>
                        <td><a target='_blank' href='http://vimeo.com/$video->video'>http://vimeo.com/$video->video</a></td>
                        <td><button class='btn btn-danger'>Excluir<input type='hidden' value='$video->id'></button></td>
                    </tr>";
                    break;
            }
        }
        return $videos;
    }

    /**
     *
     * imagem_upload
     *
     * Faz o uploade da imagem e chama outra função para criar as miniaturas
     *
     *
     * @access  private
     * @param   $string, $string, $array
     * @return  $string
     */
    public function imagem_upload($name, $id = null, $miniaturas) {

        //print_r($miniaturas);
        //exit();
        // print_r($miniaturas); exit;
        // PEGA AS INFORMAÇÕES DA IMAGEM
        $tabela = $this->uri->segment(2);
        $id = $this->uri->segment(4, $id);

        // CRIA O DIRETÓRIO DE UPLOAD
        if (!is_dir('./uploads')) {
            mkdir('./uploads');
        }

        // CRIA O DIRETÓRIO DA TABELA
        if (!is_dir('./uploads/' . $tabela)) {
            mkdir('./uploads/' . $tabela);
        }

        // CRIA O DIRETÓRIO DO ID
        if (!is_dir('./uploads/' . $tabela . '/' . $id)) {
            mkdir('./uploads/' . $tabela . '/' . $id);
        }

        // CRIA O DIRETÓRIO DA THUMB DO CODEMIN
        if (!is_dir('./uploads/' . $tabela . '/' . $id . '/mini_thumbs')) {
            mkdir('./uploads/' . $tabela . '/' . $id . '/mini_thumbs');
        }

        // CRIA O DIRETÓRIO DA MICRO THUMB DO CODEMIN
        if (!is_dir('./uploads/' . $tabela . '/' . $id . '/micro_thumbs')) {
            mkdir('./uploads/' . $tabela . '/' . $id . '/micro_thumbs');
        }

        $config['upload_path'] = './uploads/' . $tabela . '/' . $id; //Caminho onde será salvo
        $config['allowed_types'] = 'gif|jpg|png'; //Tipos de imagem aceito
        $config['overwrite'] = FALSE; //Não sobre-escrever o arquivo

        $file = $name; // Nome do campo INPUT do formulário
        $this->load->library('upload');
        $this->upload->initialize($config);

        $this->upload->do_upload($file);

        $dados = $this->upload->data();

        $this->load->library('image_lib');

        $config = $this->config;
        $configuracoes = 0;

        foreach ($config->config as $key => $value) {
            if (stripos($key, 'images-') !== false && $value) {
                if (stripos($key, 'images-' . $tabela) !== false && $value) {
                    $id_opcao = $configuracoes;
                }
                $configuracoes ++;
            }
        }

        $sizes = $this->verificaImagem($id_opcao);

        if ($sizes && count($sizes) > 0) {
            foreach ($sizes as $value) {
                $this->redimencionar_imagem($value->nome, $tabela, $id, $dados, $value->largura, $value->altura);
            }
        }

        foreach ($miniaturas as $miniatura) {
            // Cria o diretório para uma miniatura
            if (!is_dir('./uploads/' . $tabela . '/' . $id . '/' . $miniatura[0])) {
                mkdir('./uploads/' . $tabela . '/' . $id . '/' . $miniatura[0]);
            }
            $this->redimencionar_imagem($miniatura[0], $tabela, $id, $dados, $miniatura[1], $miniatura[2], $miniatura[3]);
        }


        // Cria a mini thubms
        $this->redimencionar_imagem('mini_thumbs', $tabela, $id, $dados, 180, 122, true);

        // Cria a micro thubms
        $this->redimencionar_imagem('micro_thumbs', $tabela, $id, $dados, 50, 35, true);

        return $dados['file_name'];
    }

    public function verificaImagem($opcao) {
        $this->db->select('images_config.nome, images_config.largura, images_config.altura');
        $this->db->like('json_pages', $opcao);
        return $this->db->get('images_config')->result();
    }

    /**
     *
     * redimencionar_imagem
     *
     * Pega os dados do upload e redimenciona e corta
     *
     *
     * @access  private
     * @param   $array
     * @return  null
     */
    private function redimencionar_imagem($nome_miniatura, $tabela, $id, $dados, $largura, $altura, $recortar = false) {

        switch ($dados['file_type']) {
            case 'image/png':
                $ext = '.png';
                break;
            case 'image/gif':
                $ext = '.gif';
                break;
            case 'image/jpeg':
                $ext = '.jpg';
            default:
                $ext = '.jpg';
                break;
        }

        if (!$recortar) {

            if (!empty($dados['full_path']) && $dados['full_path'] != '' && $dados['full_path'])
                list($src_width, $src_height, $type) = @getimagesize($dados['full_path']);

            $src_height = $src_height < $altura ? $src_height : $altura;
            $src_width = $src_width < $largura ? $src_width : $largura;

            // Dimencionar a imagem
            $config['image_library'] = 'GD2';
            $config['source_image'] = $dados['full_path'];
            $config['new_image'] = './uploads/' . $tabela . '/' . $id . '/' . $nome_miniatura . '.jpg';
            $config['thumb_marker'] = null;
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $src_width;
            $config['height'] = $src_height;
            $this->image_lib->initialize($config);

            if ($this->image_lib->resize()) {

                $this->createImageBackground($nome_miniatura, $largura, $altura);

                $stamp = imagecreatefromjpeg('./uploads/' . $tabela . '/' . $id . '/' . $nome_miniatura . '.jpg');
                $im = imagecreatefromjpeg('./uploads/fundos/' . $nome_miniatura . '.jpg');

                list($src_width, $src_height, $type) = @getimagesize('./uploads/' . $tabela . '/' . $id . '/' . $nome_miniatura . '.jpg');
                list($dst_width, $dst_height, $type_dst) = @getimagesize('./uploads/fundos/' . $nome_miniatura . '.jpg');

                $largura = $dst_width - $src_width;
                $altura = $dst_height - $src_height;

                $marge_right = $largura / 2;
                $marge_bottom = $altura / 2;
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);

                imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

                imagejpeg($im, './uploads/' . $tabela . '/' . $id . '/' . $nome_miniatura . '.jpg');
                imagedestroy($im);

            }
        } else {

            // Pegar largura e altura da imagem
            $imgWid = $dados["image_width"];
            $imgHei = $dados["image_height"];

            // Definir altura e largura limites
            $widthExt = $largura;
            $heightExt = $altura;
            // Fazer os cálculos das novas dimenções da imagem
            if ((($imgHei * $widthExt) / $imgWid) >= $heightExt) {
                $finalHeight = ($imgHei * $widthExt) / $imgWid;
                $finalWidth = $widthExt;
                $finalY = ($finalHeight - $heightExt) / 2;
                $finalX = 0;
            } else {
                $finalWidth = ($imgWid * $heightExt) / $imgHei;
                $finalHeight = $heightExt;
                $finalX = ($finalWidth - $widthExt) / 2;
                $finalY = 0;
            }
            // Dimencionar a imagem
            $config['image_library'] = 'GD2';
            $config['source_image'] = $dados['full_path'];
            $config['new_image'] = './uploads/' . $tabela . '/' . $id . '/' . $nome_miniatura . '/' . $dados['file_name'];
            $config['thumb_marker'] = null;
            $config['create_thumb'] = TRUE;
            $config['width'] = $finalWidth;
            $config['height'] = $finalHeight;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            // Fazer o corte
            $config['source_image'] = './uploads/' . $tabela . '/' . $id . '/' . $nome_miniatura . '/' . $dados['file_name'];
            $config['maintain_ratio'] = FALSE;
            $config['create_thumb'] = FALSE;
            $config['width'] = $widthExt;
            $config['height'] = $heightExt;
            $config['x_axis'] = $finalX;
            $config['y_axis'] = $finalY;
            $this->image_lib->initialize($config);
            $this->image_lib->crop();
            $this->image_lib->clear();
        }
    }

    private function createImageBackground($param, $l, $a) {

        if (is_file('uploads/fundos/' . $param . '.jpg')) {
            unlink('uploads/fundos/' . $param . '.jpg');
        }

        $img = imagecreatetruecolor($l, $a);
        $color = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $color);

        if (!is_dir('./uploads')) {
            mkdir('./uploads');
        }
        if (!is_dir('./uploads/fundos')) {
            mkdir('./uploads/fundos', 0777, TRUE);
        }
        imagejpeg($img, './uploads/fundos/' . $param . '.jpg');
    }

    /**
     *
     * arquivo_upload
     *
     * Faz o uploade de arquivos
     *
     *
     * @access  private
     * @param   $string, $string
     * @return  $string
     */
    public function arquivo_upload($name, $id = null) {


        // PEGA AS INFORMAÇÕES DO ARQUIVO
        $tabela = $this->uri->segment(2);
        $id = $this->uri->segment(4, $id);

        // CRIA O DIRETÓRIO DE UPLOAD
        if (!is_dir('./uploads')) {
            mkdir('./uploads');
        }

        // CRIA O DIRETÓRIO DA TABELA
        if (!is_dir('./uploads/' . $tabela)) {
            mkdir('./uploads/' . $tabela);
        }


        // CRIA O DIRETÓRIO DO ID
        if (!is_dir('./uploads/' . $tabela . '/' . $id)) {
            mkdir('./uploads/' . $tabela . '/' . $id);
        }

        $config['upload_path'] = './uploads/' . $tabela . '/' . $id; //Caminho onde será salvo
        $config['allowed_types'] = '*'; //Tipos de imagem aceito
        $config['overwrite'] = FALSE; //Não sobre-escrever o arquivo

        $file = $name; // Nome do campo INPUT do formulário
        $this->load->library('upload');
        $this->upload->initialize($config);



        $this->upload->do_upload($file);

        $dados = $this->upload->data();

        return $dados['file_name'];
    }

    /**
     * pr
     *
     * print_r
     *
     *
     * @param   array
     * @return  array
     */
    function pr($string) {
        echo '<pre>';
        print_r($string);
        echo '</pre>';
    }

}

/* End of file ./application/libraries/codemin.php */