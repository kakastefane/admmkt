<?php header("Content-Type:text/plain"); ?>
User-agent: * <?= PHP_EOL ?>
<?php foreach ($allow as $item) { ?>
Allow: <?= $item . PHP_EOL ?>
<?php } ?>
<?php foreach ($disallow as $item) { ?>
Disallow: <?= $item .  PHP_EOL ?>
<?php } ?>