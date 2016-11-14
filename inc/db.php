<?php 
require 'medoo.php';
global $database;

$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => '',
    'server' => '',
    'username' => '',
    'password' => '',
    'charset' => 'utf8'
]);

?>