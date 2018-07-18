<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 03.02.2018
 * Time: 18:07
 */

//namespace genealogy;


class Db
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(){
        if (!isset(self::$instance)) {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        self::$instance = new PDO('mysql:host=localhost;dbname=Stammbaum', 'ortrun', 'ortrun', $pdo_options);
    }
        return self::$instance;
    }
}