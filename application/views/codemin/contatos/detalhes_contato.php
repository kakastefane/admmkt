<h1>Contato de <?= $contato->nome ?></h1>

<hr>

<h2>Dados do contato:</h2>
<ul>
	<li><b>Nome:</b> <?= $contato->nome ?></li>
	<li><b>Email:</b> <?= $contato->email ?></li>
	<li><b>Telefone:</b> <?= $contato->telefone ?></li>
	<li><b>Data:</b> <?= data_log($contato->data_hora) ?></li>
	<li><b>Mensagem:</b> <?= $contato->mensagem ?></li>
</ul>

<hr>

<a href="./gercont/contatos" class="btn">Voltar</a>