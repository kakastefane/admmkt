<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf8">
    <title>Instalação - Codemin/Gercont 2 - Painel Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?=base_url()?>">

    <!-- CSS -->
    <link rel="stylesheet" href="./public/codemin/css/bootstrap.css">
    <style>
      .cointainer {
        margin: 0 auto;
        width: auto;
        max-width: 880px;
      }
    </style>

  </head>
  <body>

  <div class="cointainer">

    <h1>Instalação do Banco de Dados</h1>

    <h2>Instalação</h2>
    <ul>
      <li>Criar as tabelas básicas com prefixo <code><?= substr_replace($this->db->dbprefix('_'),"",-1); ?></code> configurado anteriormente, que serão responáveis pelo funcionamento do codemin</li>
      <li>Criar um usuário administrador</li>
    </ul>

    <h3>Tabelas a serem criadas:</h3>
    <ul>
      <li><code><?= $this->db->dbprefix('codemin_log_acessos') ?></code> Guarda os registros de acesso ao codemin</li>
      <li><code><?= $this->db->dbprefix('codemin_log_acoes') ?></code> Guarda os registro de ações do usuários</li>
      <li><code><?= $this->db->dbprefix('codemin_opcoes') ?></code> Guarda as opções de gerenciáveis de checkboxes, radios e selects</li>
      <li><code><?= $this->db->dbprefix('codemin_opcoes_selecionadas') ?></code> Guarda as opções selecionadas de multipla opção (checkbox)</li>
      <li><code><?= $this->db->dbprefix('codemin_uploads') ?></code> Guarda os arquivos de uploads (imagens, documentos, etc...)</li>
      <li><code><?= $this->db->dbprefix('codemin_usuarios') ?></code> Guarda os usuários do sistema</li>
      <li><code><?= $this->db->dbprefix('codemin_usuarios_permissoes') ?></code> Guarda as pemissões dos usuários no sistema</li>
    </ul>

    <h3>Tabelas do gercont:</h3>
    <ul>
      <li><code><?= $this->db->dbprefix('noticias') ?></code> Guarda as noticias</li>
      <li><code><?= $this->db->dbprefix('galerias_fotos') ?></code> Guarda as galerias de fotos</li>
      <li><code><?= $this->db->dbprefix('banners') ?></code> Guarda os banners</li>
      <li><code><?= $this->db->dbprefix('empresa') ?></code> Guarda um único registro sobre a empresa</li>
      <li><code><?= $this->db->dbprefix('contato') ?></code> Guarda um único registro com os dados de contato da empresa</li>
      <li><code><?= $this->db->dbprefix('configuracoes') ?></code> Guarda um único registro com as configurações do site</li>
    </ul>

    <hr/>
    
    <h3>Antes de começar...</h3>
    <p>Para que tudo funcione corretamente, verifique se fez todas as configurações necessárias.</p>
    <ul>
      <li>Adicionar <code>database</code> e <code>session</code> as libraries em <code>application/config/autoload.php</code></li>
      <li>Adicionar <code>url</code>, <code>form</code> e <code>codemin</code> aos helpers em <code>application/config/autoload.php</code></li>
      <li>Configurar o <code>base_url</code> e <code>encryption_key</code> em <code>application/config/config.php</code></li>
      <li>Adicionar as rotas em <code>application/config/routes.php</code></li>
      <li>Configurar o <code>hostname</code>, <code>username</code>, <code>password</code>, <code>database</code> e <code>dbprefix</code> para acesso ao banco de dados em <code>application/config/database.php</code></li>
    </ul>

    <hr/>

    <h3>Criando usuário, senha e todas as tabelas</h3>
    <ul>
      <li>O usuário deve ser todo minusculo e conter apenas letras, números, "-" e "_"</li>
      <li>A senha deve ser toda minuscula e conter apenas letras, números, "-" e "_"</li>
    </ul>

    <form class="form-horizontal" method="POST">

      <div class="control-group">
        <label class="control-label">Nome</label>
        <div class="controls">
          <input type="text" name="nome" placeholder="Nome">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Login</label>
        <div class="controls">
          <input type="text" name="login" placeholder="Login">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Senha</label>
        <div class="controls">
          <input type="password" name="senha" placeholder="Senha">
        </div>
      </div>

      <div class="control-group">
        <div class="controls">
          <button type="submit" class="btn btn-primary">Criar Usuário</button>
        </div>
      </div>

    </form>

  </div>


  <!-- JS -->
  <script src="./public/codemin/js/bootstrap.min.js"></script>

  </body>
</html>