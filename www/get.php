<?php

require ('./libs/application.php');

$template = $twig->loadTemplate('form.html');
$template->display(array('test'));
print ($template);
