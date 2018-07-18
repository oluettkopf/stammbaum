<?php

/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 09.02.2018
 * Time: 08:58
 */

use \DateTime;
include_once "Db.php";
include_once "Person.php";

class Relationship
{
    private $id;
    private $spouse;
    private $married;
    private $weddingdate;
    private $weddingAccuracy;
    private $weddingplace;
    private $divorce;
    private $notes;
    private $children = array();

    public function __construct($id,$spouse,$married,$weddingdate, $weddingAccuracy,$weddingplace,$divorce,$notes)
    {
        $this->id=$id;
        $this->spouse=$spouse;
        $this->married=$married;

        if($weddingdate!='0001-01-01' && $weddingdate!='0000-00-00' && $weddingdate!=null) {
            $this->weddingdate = new DateTime($weddingdate);
        }
        $this->weddingAccuracy=$weddingAccuracy;
        $this->weddingplace=$weddingplace;
        $this->divorce=$divorce;
        $this->notes=$notes;
    }


    public function getWeddingdate()
    {
        if ($this->weddingdate != null){
            $monthNamesDE = [
                'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
            ];
            switch ($this->weddingAccuracy) {
                case 0:
                    return $this->weddingdate->format('d.m.Y');
                    break;
                case 1:
                    return $monthNamesDE[$this->weddingdate->format('n')-1]." ".$this->weddingdate->format('Y');
                    break;
                case 2:
                    return $this->weddingdate->format('Y');
                    break;
                case 3:
                    return "um " . $this->weddingdate->format('Y');
                    break;
                default:
                    return null;
                    break;
            }
        }
    }
    public function getWeddingdateObject(){
        return $this->weddingdate;
    }

    public static function find($gender, $id){
        $relationships=array();
        $db = Db::getInstance();
        if($gender==0){
            $req = $db->prepare('SELECT * FROM relationship WHERE man = :id');
            $req->execute(array('id'=>$id));
            while ($row=$req->fetch()){
                $relationships[]=new Relationship($row['id'],Person::findByQuery($row['wife']),$row['married'],$row['weddingdate'],$row['weddingAccuracy'],$row['weddingplace'],$row['divorce'],$row['notes']);
            }
            foreach ($relationships as $relationship){
                $req2 = $db->prepare('SELECT * FROM person WHERE father = :father and mother = :mother');
                $req2->execute(array('father'=>$id,'mother'=>$relationship->getSpouse()->getId()));
                while ($row2 = $req2->fetch()){
                    $relationship->setChildren(new Person($row2['id'],$row2['firstName'],$row2['middleName'],$row2['middleName2'],$row2['middleName3'],$row2['middleName4'],
                        $row2['middleName5'],$row2['lastName'],$row2['usedName'],$row2['birthName'],$row2['gender'],$row2['birthday'],$row2['birthdayAccuracy'],$row2['birthplace'],
                        $row2['baptised'],$row2['deathdate'],$row2['deathdateAccuracy'],$row2['deathplace'],$row2['deathcause'],$row2['occupation'],$row2['notes'],
                        $row2['updated']));


                }
            }
        }
        else {
            $req = $db->prepare('SELECT * FROM relationship WHERE wife = :id');
            $req->execute(array('id'=>$id));
            while ($row=$req->fetch()){
                $relationships[]=new Relationship($row['id'],Person::findByQuery($row['man']),$row['married'],$row['weddingdate'],$row['weddingAccuracy'],$row['weddingplace'],$row['divorce'],$row['notes']);
            }
            foreach ($relationships as $relationship){
                $req2 = $db->prepare('SELECT * FROM person WHERE father = :father and mother = :mother');
                $req2->execute(array('mother'=>$id,'father'=>$relationship->getSpouse()->getId()));
                while ($row2 = $req2->fetch()){
                    $relationship->setChildren(new Person($row2['id'],$row2['firstName'],$row2['middleName'],$row2['middleName2'],$row2['middleName3'],$row2['middleName4'],
                        $row2['middleName5'],$row2['lastName'],$row2['usedName'],$row2['birthName'],$row2['gender'],$row2['birthday'],$row2['birthdayAccuracy'],$row2['birthplace'],
                        $row2['baptised'],$row2['deathdate'],$row2['deathdateAccuracy'],$row2['deathplace'],$row2['deathcause'],$row2['occupation'],$row2['notes'],
                        $row2['updated']));


                }
            }
        }

        return $relationships;
    }

    /**
     * @param array $children
     */
    public function setChildren($child)
    {
        $this->children[] = $child;
    }
     static function cmp_age($a, $b){
        if ($a->getBirthdate() == $b->getBirthdate()) {
            return 0;
        }
        return ($a->getBirthdate() > $b->getBirthdate()) ? +1 : -1;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        $children = $this->children;
            usort($children,  array($this,"cmp_age"));
        return $children;
    }
    /**
     * @return mixed
     */
    public function getSpouse()
    {
        return $this->spouse;
    }

    /**
     * @return mixed
     */
    public function getMarried()
    {
        return $this->married;
    }

    public function __toString()
    {
        $string = !empty($this->weddingdate) || !empty($this->weddingplace) ? " Hochzeit: ".$this->getWeddingdate() : "";
        $string.= !empty($this->weddingplace) ? " in ".$this->weddingplace : "";

        return $string;

    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }
}