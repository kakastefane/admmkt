## Instalação

### 1. Configurar a url do site

*   `application/config/config.php:18`

### 2. Criar e configurar o banco de dados

*   Criar o banco de dados vazio em utf8
*   Configurar os dados de acesso em `application/config/database.php`

### 3. Configurar módulos do site

*   Liberar ou bloquear os módulos do site em `application/config/gercont.php`

### 4. Rodar o instalador para as tabelas do site e primeiro usuário admin

*   Acessar `http://raizdosite.com.br/instalar` e seguir as instruções
*	Deixar a função instalar como `private` ao invéz de `public` para não ser executada novamente em `application/controllers/codemin/inicial.php:6`

### 5. Acessar o administrador e configurar o básico

*   Acessar `http://raizdosite.com.br/administrador` e seguir as instruções

* * *

## Utilização das listagens

### Exemplo de utilização da listagem com paginação para notícias:
	
	
	:::php
	<?php
	$noticias = array(
	
		// Pega resultados da tabela noticias
		'tabela' => 'noticias',
		
		// Com o layout em applicaiton/views/templates/noticias/_item_home.php
		'layout' => 'item_home',
		
		// Link para o namespace do registro (url) e link da rota
		'links' => array(
			// Verificar em application/controllers/codemin/administrador.php qual item tem url_amigavel
			'url' => 'url_titulo',
			
			// Verificar rotas em application/language/
			'link' => $this->lang->line('rota_noticias') 
		),
		
		// Listar dois resultados por linha
		// Cria uma variarel '{quebra_linha}' com o valor 'primeiro' para o primeiro resultado de cada linha
		'por_linha' => 2, 
		
		// Exibir 2 linhas por pagina (com 2 resultados por linha, como configurado acima)
		'quantidade_linha' => 2,
		
		// Trás no template o url das miniaturas passadas no array como {min_thumb} nesse caso
		// Verificar miniaturas em application/controllers/codemin/administrador.php
		'miniaturas' => array('thumb'),
		
		// Ordena as notícias criadas pela data decrescente
		'ordenar' => array('noticias.data','desc') 
		
	);
	echo $this->gercont->listagem($noticias); ?>
	
	<?= $this->pagination->create_links(); // Exibi a paginação ?>
	
	
### Exemplo de utilização da listagem de outras noticias (em detalhes de uma notícia por exemplo):
	
	
	:::php
	<?php
	$noticias = array(
	
		// Pega resultados da tabela noticias
		'tabela' => 'noticias',
		
		// Com o layout em applicaiton/views/templates/noticias/_item_outros.php
		'layout' => 'item_outros',
		
		// Link para o namespace do registro (url) e link da rota
		'links' => array(
			// Verificar em application/controllers/codemin/administrador.php qual item tem url_amigavel
			'url' => 'url_titulo',
			
			// Verificar rotas em application/language/
			'link' => $this->lang->line('rota_noticias') 
		),
		
		// Trás apenas os 5 primeiros registros
		'limit' => 5,
		
		// Trás no template o url das miniaturas passadas no array como {min_thumb} nesse caso
		// Verificar miniaturas em application/controllers/codemin/administrador.php
		'miniaturas' => array('thumb'),
		
		// Trás as notícias aleatóriamente
		'ordenar' => array('noticias.titulo','rand'),
		
		// Trás somente as notícias em que o id seja diferente da atual
		'where' => array('noticias.id !=' => $noticia->id)
		
	);
	echo $this->gercont->listagem($noticias); ?>
	
	
### Exemplo de utilização para listagem de banners

	:::php
	<?php
	$banners = array(
		
		// Pega resultados da tabela banners
		'tabela' => 'banners',
		
		// Com o layout em applicaiton/views/templates/banners/_item_home.php
		'layout' => 'item_home',
		
		// Link para rotas
		'links' => array(
			'url' => 'link' // No caso vai ser apenas o link digitado no campo link
		),
		
		// Trás no template o url das miniaturas passadas no array como {min_banner} nesse caso
		// Verificar miniaturas em application/controllers/codemin/administrador.php
		"miniaturas" => array("banner"),
		
		// Trás apenas os 5 primeiros registros
		'limit' => 5,
		
		// Trás os banners na ordem da ordenação (arrastar para ordenar no admin)
		'ordenar' => array('banners.ordenar','asc')
	);
	echo $this->gercont->listagem($banners); ?>
	
* * *
	
## Tipos de entrada de dados Disponíveis (application/controllers/administrador.php)

### Input Text

**Tipo:** `input-text`

Entrada de texto simples, gera no banco de dados um campo varchar de 255.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   URL Amigável `url_amigavel (boolean)`
*   Place Holder `placeholder (string)`
*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Vídeo do Vimeo

**Tipo:** `video-vimeo`

Entrada de texto simples, gera no banco de dados um campo varchar de 255. Aceita somente link de vídeo do vimeo, ao gravar limpa a URL e grava somente o id do vídeo.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Place Holder `placeholder (string)`
*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Vídeo do Youtube

**Tipo:** `video-youtube`

Entrada de texto simples, gera no banco de dados um campo varchar de 255. Aceita somente link de vídeo do youtube, ao gravar limpa a URL e grava somente o id do vídeo.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Place Holder `placeholder (string)`
*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Vídeo do Youtube/Vimeo

**Tipo:** `video`

Entrada de texto simples, gera no banco de dados um campo varchar de 255. Aceita somente link de vídeo do youtube ou vimeo, ao gravar limpa a URL e grava somente o id do vídeo.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Place Holder `placeholder (string)`
*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Vídeos

**Tipo:** `videos`

Galeria de vídeos do youtube e vimeo por ajax em um modal. Disponível somente na edição de um registro.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Dica `dica (string)`

* * *

### Data

**Tipo:** `data`

Entrada de texto simples com máscara para data no formato dd/mm/aaaa usando o meioMask. Faz a conversão para aaaa-mm-dd para guardar no banco em um campo gerado do tipo date.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Place Holder `placeholder (string)`
*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Monetário

**Tipo:** `monetario`

Entrada de texto simples com máscara para decimal no formato 999.999.999.999,99 usando o meioMask. Faz a conversão para 999999999999.99 para guardar no banco em um campo gerado do tipo decimal 10,2.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Textarea

**Tipo:** `text-area`

Entrada de texto textarea. Cria campo no banco de dados do tipo longtext.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Place Holder `placeholder (string)`
*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Textarea Rich

**Tipo:** `text-area-rick`

Entrada de texto textarea com o editor CKEDITOR e galeria de imagens. Cria campo no banco de dados do tipo longtext.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Select Estático

**Tipo:** `select`

Select com array manual ou vindo de outra tabela com a função `$this->codemin->array_select('nome_tabela','nome_campo')`

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`
*   Dados `dados (array)`

#### Opções

*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Select Dinâmico

**Tipo:** `select-dinamico`

Select com opções gerenciáveis pelo usuário

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Checkbox Estático

**Tipo:** `checkbox`

Checkboxes com array manual ou vindo de outra tabela com a função `$this->codemin->array_select('nome_tabela','nome_campo')`

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`
*   Dados `dados (array)`

#### Opções

*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Radio Estático

**Tipo:** `radio`

Radios com array manual ou vindo de outra tabela com a função `$this->codemin->array_select('nome_tabela','nome_campo')`

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`
*   Dados `dados (array)`

#### Opções

*   Dica `dica (string)`
*   Validação `validacao (string)`

* * *

### Imagem

**Tipo:** `imagem`

Upload de imagem comum. Pode-se passar um array com os parâmetros nome da miniatura(string), largura(int), altura(int) e recortar(boolean) `array('nome',400,100,true)`

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Miniaturas `miniaturas (array)`
*   Dica `dica (string)`

* * *

### Imagens

**Tipo:** `imagens`

Galeria de imagens por ajax em um modal. Disponível somente na edição de um registro.

#### Obrigatório

*   Título `titulo (string)`
*   Campo `campo (string)`

#### Opções

*   Dica `dica (string)`

* * *

## Exemplo de uma função para o controller administrador

      public function exemplos(){

      // Título
      $dados[] = array(
        'titulo' => 'Título',
        'campo' => 'titulo',
        'url_amigavel' => true,
        'tipo' => 'input-text',
        'validacao' => 'required|',
        'dica' => 'O Título deve conter no máximo 250 caractéres',
        'placeholder' => 'Título da Galeria'
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
        'imagens' => true, // Botão para galeria de imagens abaixo do editor de texto
        'tipo' => 'text-area-rich',
        'placeholder' => 'Texto completo',
        'dica' => 'Texto completo com imagens, iframes e tudo o que sua criatividade e o html permitirem'
      );
      // Vídeo do youtube
      $dados[] = array(
        'titulo' => 'Video Youtube',
        'campo' => 'video_youtube',
        'tipo' => 'video-youtube',
        'placeholder' => 'http://www.youtube.com/watch?v=_cPxKq-gMDo',
        'dica' => 'Deve ser uma url absoltua do youtube'
      );
      // Vídeo do vimeo
      $dados[] = array(
        'titulo' => 'Video vimeo',
        'campo' => 'video_vimeo',
        'tipo' => 'video-vimeo',
        'placeholder' => 'http://vimeo.com/79888071',
        'dica' => 'Deve ser uma url absoltua do vimeo'
      );
      // Valor
      $dados[] = array(
        'titulo' => 'Valor',
        'campo' => 'valor',
        'tipo' => 'monetario',
        'dica' => 'Digite apenas número, sem pontos ou vírgulas'
      );
      // Select estático
      $array_select = array(0 => 'Norte', 1 => 'Sul');
      $dados[] = array(
        'titulo' => 'Situação',
        'campo' => 'local',
        'tipo' => 'select',
        'dados' => $array_select,
        'dica' => 'Os dados desse select foram passados por um array estático'
      );
      // Select dinâmico
      $dados[] = array(
        'titulo' => 'Categoria',
        'campo' => 'categoria',
        'tipo' => 'select-dinamico',
        'dica' => 'Clique em gerenciar para adicionar ou remover categorias'
      );
      // Checkbox estático
      $array_select = array(0 => 'Azul', 1 => 'Verde', 2 => 'Vermelho');
      $dados[] = array(
        'titulo' => 'Cor',
        'campo' => 'cor',
        'tipo' => 'checkbox',
        'dados' => $array_select,
        'dica' => 'Os dados desses checkboxes foram passados por um array estático'
      );
      // Checkbox estático
      $array_select = array(0 => 'Claro', 1 => 'Escuro', 2 => 'Neutro');
      $dados[] = array(
        'titulo' => 'Grupo',
        'campo' => 'grupo',
        'tipo' => 'radio',
        'dados' => $array_select,
        'dica' => 'Os dados desses radios foram passados por um array estático'
      );
      // Imagem
      $miniaturas[] = array('corte',400,100,true); // criar versão de exatos 400x100px
      $miniaturas[] = array('nao_corte',400,400); // criar versão de no máximo 400x400px
      $dados[] = array(
        'titulo' => 'Capa',
        'campo' => 'capa',
        'tipo' => 'imagem',
        'miniaturas' => $miniaturas,
        'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif'
      );
      // Imagens (galeria de imagens)
      $dados[] = array(
        'titulo' => 'Imagens da Notícia',
        'campo' => 'imagens',
        'tipo' => 'imagens',
        'dica' => 'Clique em Gerenciar Imagens para selecionar imagens para upload'
      );
	  
	  $config = array(
	  	'listagem' => array('titulo','data'), // O que vai aparecer na listagem
	  	'titulo' => 'Exemplos', // Titulo da página
		'ordenar_drag' => true, // dá a opção de ordernar por arrastar
		'order_by' => array( 'exemplos.data' => 'DESC' ) // Ordenar registros por data decrescente (somente se ordenar_drag for false)
	  );

      /*
      * $this->codemin->montar_codemin = Cria um CRUD com os input passados para vários registros
      *
      * $dados = os inputs que o usuário irá usar para gerenciar o site
      *
      * $config = array com as configurações das views
      *
      */
      $this->codemin->montar_codemin($dados,$config);

    }
