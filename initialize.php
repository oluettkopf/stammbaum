<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 11.09.2017
 * Time: 13:53
 */
namespace Stammbaum;
use \DateTime;
include_once ("Relationship.php");
include_once ("Person.php");
include_once ("Image.php");


$db = mysqli_connect ('localhost','ortrun','ortrun', 'Stammbaum');

if ( $db ){
    //echo 'Verbindung erfolgreich: ';
    // print_r( $db);
    mysqli_set_charset($db, 'utf8');
}
else{
    die('keine Verbindung möglich: ' . mysqli_error());
}

$data = array();
$father = array();
$mother = array();
$result = $db->query("SELECT * FROM person");
if ($result->num_rows>0) {
    while($row = $result->fetch_assoc()) {

        //$data[]=new Person(intval($row['id']),$row['firstName'],$row['middleNames'],$row['lastName'],$row['birthName'],intval($row['gender']),$row['birthday'],
                                //$row['birthplace'],$row['deathdate'],$row['deathplace'],$row['occupation'],$row['notes'],$row['updated']);
        $birthday= (!empty($row['birthday']) && $row['birthday']!='0000-00-00') ? new DateTime($row['birthday']) : "";
        $deathdate=(!empty($row['deathdate']) && $row['deathdate']!='0000-00-00') ? new DateTime($row['deathdate']) : "";
        $baptised=(!empty($row['baptised']) && $row['baptised']!='0000-00-00') ? new DateTime($row['baptised']) : "";
        $data[$row['id']]=new Person(intval($row['id']),$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],$row['middleName5'],
            $row['lastName'],$row['usedName'],$row['birthName'],intval($row['gender']), $birthday,intval($row['birthdayAccuracy']),$row['birthplace'],$baptised,
            $deathdate,intval($row['deathdateAccuracy']),$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],new DateTime($row['updated']));


        if(!empty($row['father'])){
            $father[] = array(intval($row['id']),intval($row['father']));
        }
        if(!empty($row['mother'])){
            $mother[] = array(intval($row['id']),intval($row['mother']));
        }
    }
}

foreach ($father as $row){
   // $data[$row[0]]->setFather($data[$row[1]]);
    Person::getAllPersons()[$row[0]]->setFather(Person::getAllPersons()[$row[1]]);
}
foreach ($mother as $row){
    // $data[$row[0]]->setFather($data[$row[1]]);
    Person::getAllPersons()[$row[0]]->setMother(Person::getAllPersons()[$row[1]]);
}


$result = $db->query("SELECT * FROM relationship");
if ($result->num_rows>0) {
    while($row = $result->fetch_assoc()) {
        //$data[]=new Relationship($row['id'],Person::$allPersons[$row['man']],Person::$allPersons[$row['wife']],$row['weddingdate'],$row['weddingplace'],$row['divorce'],$row['notes']);
        $wdate= (!empty($row['weddingdate']) && $row['weddingdate']!='0000-00-00') ? new DateTime($row['weddingdate']) : "";
        //$divdate = (!empty($row['divorce']) && $row['divorce']!='0000-00-00') ? new DateTime($row['divorce']) : "";
        Person::getAllPersons()[$row['man']]->addRelationship(new Relationship(intval($row['id']),Person::getAllPersons()[$row['wife']],$row['married'],$wdate,$row['weddingAccuracy'],$row['weddingplace'],$row['divorce'],$row['notes']));
        Person::getAllPersons()[$row['wife']]->addRelationship(new Relationship(intval($row['id']),Person::getAllPersons()[$row['man']],$row['married'],$wdate,$row['weddingAccuracy'],$row['weddingplace'],$row['divorce'],$row['notes']));
    }
}

$result = $db->query("SELECT * FROM images i inner join person_in_image on imgId=i.id");
if ($result->num_rows>0) {
    while($row = $result->fetch_assoc()) {

        Person::getAllPersons()[$row['personId']]->addImage(new Image(intval($row['imgId']),$row['url'],$row['extension'],$row['description'],$row['imgtype'],$row['year'],$row['yearAccuracy']));
    }
}


echo "<pre>";
/*
$string=Person::getAllPersons()[39]->getBirthday();
$start=new DateTime($string);
$end=new DateTime(Person::getAllPersons()[39]->getDeathdate());
var_dump($start);
var_dump($start->diff($end));*/
//print_r(Person::getAllPersons());
echo "</pre>";

