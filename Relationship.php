<?php
namespace Stammbaum;
include_once ("functions.php");
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 11.09.2017
 * Time: 13:44
 */
class Relationship
{
    private $id;
    private $partner;
    private $married;
    private $weddingdate;
    private $weddingAccuracy;
    private $weddingplace;
    private $divorce;
    private $divorceAccuracy;
    private $notes;

    public function __construct(int $id, Person $partner, $married, $weddingdate, $weddingAccuracy, $weddingplace, $divorce, $notes){
        $this->id = $id;
        $this->partner = $partner;
        $this->married=$married;
        $this->weddingdate = $weddingdate;
        $this->weddingAccuracy = $weddingAccuracy;
        $this->weddingplace = $weddingplace;
        $this->divorce = $divorce;
        //$this->divorceAccuracy = $divorceAccuracy;
        $this->notes = $notes;

    }

    /**
     * @return mixed
     */
    public function getMarried()
    {
        return $this->married;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Person
     */
    public function getPartner(): Person
    {
        return $this->partner;
    }

    /**
     * @return mixed
     */
    public function getWife()
    {
        return $this->wife;
    }

    /**
     * @return mixed
     */
    public function getWeddingdate()
    {
        if (!empty($this->weddingdate)){
            if ($this->weddingAccuracy==0) return $this->weddingdate->format('d.m.Y');
            elseif ($this->weddingAccuracy==1)  return $this->weddingdate->format('m.Y');
            elseif ($this->weddingAccuracy==2)  return $this->weddingdate->format('Y');
            elseif ($this->weddingAccuracy==3)  return "um ".$this->weddingdate->format('Y');
        }
        else return  null;
    }

    /**
     * @return mixed
     */
    public function getWeddingplace()
    {
        return $this->weddingplace;
    }

    public function getWedding(){
        return !empty($this->weddingdate) ? "&#9901; ".($this->getWeddingdate())." ".$this->weddingplace : " ";
    }

    /**
     * @return mixed
     */
    public function getDivorce()
    {
        /*if (!empty($this->divorce)){
            if ($this->divorceAccuracy==0) return $this->divorcedate->format('d.m.Y');
            elseif ($this->divorceAccuracy==1)  return $this->divorcedate->format('m.Y');
            elseif ($this->divorceAccuracy==2)  return $this->divorcedate->format('Y');
            elseif ($this->divorceAccuracy==3)  return $this->divorcedate->format('ca. Y');
        }
        else return  null;*/
        return $this->divorce;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    public function getChildren(Person $active){
        $children = array();
        if ($this->partner->getGender()==1) {
            foreach (Person::getAllPersons() as $person) {
                if ($active == $person->getFather() && $this->partner == $person->getMother()) {
                    $children[] = $person;
                }
            }
        }
        elseif ($this->partner->getGender()==0) {
            foreach (Person::getAllPersons() as $person) {
                if ($active == $person->getMother() && $this->partner == $person->getFather()) {
                    $children[] = $person;
                }
            }
        }
        usort($children, array($this, "cmp_age"));
        return $children;
    }
    static function cmp_age($a, $b){
        if ($a->getBirthdate() == $b->getBirthdate()) {
            return 0;
        }
        return ($a->getBirthdate() > $b->getBirthdate()) ? +1 : -1;
    }

    public function getWeddingdateObject(){
        return $this->weddingdate;
    }
}