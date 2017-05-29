    <div class="container">

     <!-- FOOTER -->
      <footer>
        <p><?= $configuracoes->texto_rodape ?></p>
        <p>
          <a href="<?= base_url() ?>?idioma=english">English</a>
          <a href="<?= base_url() ?>?idioma=portugues-br">Português</a>
	  <a href="<?= base_url() ?>?idioma=espanol">Español</a>
        </p>
      </footer>

    </div><!-- /.container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="./public/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    <?php if(isset($js)){ echo $js; } ?>
    <script src="./public/js/funcoes.js"></script>
  </body>
</html>