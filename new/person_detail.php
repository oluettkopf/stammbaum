<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 04.02.2018
 * Time: 16:58
 */

include_once "Person.php";

require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__.'/tpl/');

$twig = new Twig_Environment($loader);

$id = $_GET['id'];
$p = Person::findById($id);
$p2 = Person::getAllData()[$id];
/*echo "<pre>";
print_r($p2->getImages2());
echo "</pre>";*/
echo $twig->render('person_detail.twig', array('person' => $p, 'p2' => $p2, 'all' => Person::getAllData()));
