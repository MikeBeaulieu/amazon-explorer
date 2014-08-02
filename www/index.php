<?php

// put full path to Smarty.class.php
require('/var/www/libs/smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('./templates');
$smarty->setCompileDir('./templates_c');
//$smarty->setCacheDir('/web/www.example.com/smarty/cache');
//$smarty->setConfigDir('/web/www.example.com/smarty/configs');

$smarty->assign('name', 'Ned');
$smarty->display('index.tpl');

?>