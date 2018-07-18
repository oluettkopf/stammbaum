<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 15.07.2018
 * Time: 17:34
 */

require_once "Place.php";
require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__.'/tpl/');
$twig = new Twig_Environment($loader);

$places=Place::findAllPlaces();
echo $twig->render('place_list.twig', array('places' => $places));