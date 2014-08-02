<?php
// get generic stuff

require('/var/www/amazon.mayuli.com/vendor/autoload.php');
$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader, array(
    'debug' => true,
//  'cache' => '../cache',
));

$twig->addExtension(new Twig_Extension_Debug());