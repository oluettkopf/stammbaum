<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 04.02.2018
 * Time: 14:11
 */
//namespace genealogy;
include_once "Person.php";

require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__.'/tpl/');

$twig = new Twig_Environment($loader);



$request_uri = parse_url( $_SERVER['REQUEST_URI']);
//var_dump($request_uri);

$controller = $_GET['c'];
//var_dump($controller);
switch ($controller){
    case 'detail': require "person_detail.php"; break;
    case 'list': require "list.php"; break;
    default: echo "file not found"; break;
}



//$p=Person::allPersons();
//echo $twig->render('list.twig', array('persons' => $p));