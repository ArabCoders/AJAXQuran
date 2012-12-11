<?php
require_once (ROOT.'/vendor/autoload.php');
require_once (ROOT.'/vendor/asset/functions.php');

$loader = new Twig_Loader_Filesystem(ROOT.'/vendor/asset/views/');
$twig = new Twig_Environment($loader, array(
	'autoescape' 	=> false,
	'auto_reload'	=> true,
));

$db = new SQLite3(ROOT . '/vendor/asset/quran.db');

#EOF;