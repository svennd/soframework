<?php
$core->load_modules('cache');
$core->view->use_page('menu');
$core->view->use_page('index');

$core->cache->save('test', "inhoud van speciaal save :)");
echo $core->cache->get('test');
echo $core->cache->clean();
?>