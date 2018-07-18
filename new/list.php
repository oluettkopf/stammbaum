<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 08.02.2018
 * Time: 11:02
 */

include_once "Person.php";

require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__.'/tpl/');

$twig = new Twig_Environment($loader);

$p=Person::allPersons();
echo $twig->render('list.twig', array('persons' => $p));