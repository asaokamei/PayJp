<?php
use Composer\Autoload\ClassLoader;

require_once dirname(__DIR__).'/vendor/autoload.php';

$loader = new ClassLoader();

$loader->addPsr4('tests\\', __DIR__);
$loader->register();

