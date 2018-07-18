<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 15.07.2018
 * Time: 13:44
 */

require_once 'Place.php';
require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__.'/tpl/');

$twig = new Twig_Environment($loader);

$id = $_GET['id'];

$place=Place::findById($id);
echo $twig->render('place_detail.twig', array('place' => $place));