<?php
spl_autoload_register(function ($class_name) {

$dirs = array(
'../src/Model/',
'../src/Config/',
'../src/Controller/',
);

foreach ($dirs as $dir) {
if (file_exists($dir . $class_name . '.php')) {
require_once($dir . $class_name . '.php');
return;
}
}
});