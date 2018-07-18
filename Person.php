<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 13.09.2017
 * Time: 14:44
 */

namespace Stammbaum;
use \DateTime;
include_once ("Image.php");


class Person
{
    private static $allPersons=array();

    private $id;
    private $firstname;
    private $middlename;
    private $middlename2;
    private $middlename3;
    private $middlename4;
    private $middlename5;
    private $lastname;
    private $usedname;
    private $birthname;
    private $gender;
    private $birthday;
    private $birthdayAccuracy;
    private $birthplace;
    private $baptised;
    private $deathdate;
    private $deathdateAccuracy;
    private $deathplace;
    private $deathcause;
    private $occupation;
    private $notes;
    private $updated;
    private $father;
    private $mother;
    private $relationships = array();
    private $images = array();

    public function __construct(int $id, $firstname, $middlename, $middlename2, $middlename3, $middlename4, $middlename5,$lastname, $usedname,
                                 $birthname,int $gender,$birthday, $birthdayAccuracy, $birthplace,$baptised,$deathdate, $deathdateAccuracy,
                                 $deathplace,$deathcause,$occupation, $notes,$updated)
    {
        $this->id=$id;
        $this->firstname=$firstname;
        $this->middlename=$middlename;
        $this->middlename2=$middlename2;
        $this->middlename3=$middlename3;
        $this->middlename4=$middlename4;
        $this->middlename5=$middlename5;
        $this->lastname=$lastname;
        //$this->lastname=str_replace(" ","&nbsp",$lastname);
        $this->usedname=$usedname;
        $this->birthname=$birthname;
        $this->gender=$gender;
        $this->birthday=$birthday;
        $this->birthdayAccuracy=$birthdayAccuracy;
        $this->birthplace=$birthplace;
        $this->baptised=$baptised;
        $this->deathdate=$deathdate;
        $this->deathdateAccuracy=$deathdateAccuracy;
        $this->deathplace=$deathplace;
        $this->deathcause=$deathcause;
        $this->occupation=$occupation;
        $this->notes=$notes;
        $this->updated=$updated;

        self::$allPersons[$id]=$this;
    }

    /**
     * @return array
     */
    public static function getAllPersons(): array
    {
        return self::$allPersons;
    }

    /**
     * @param mixed $father
     */
    public function setFather($father)
    {
        $this->father = $father;
    }

    /**
     * @param mixed $mother
     */
    public function setMother($mother)
    {
        $this->mother = $mother;
    }

    /**
     * @return mixed
     */
    public function getFather()
    {
        return $this->father;
    }
    /**
     * @return mixed
     */
    public function getMother()
    {
        return $this->mother;
    }

    public function addRelationship(Relationship $partner){
        $this->relationships[]=$partner;
    }

    public function getDataForTree(){
        $string="<span class='name'>";
        switch($this->usedname){
            case 0: $string.=$this->firstname." "; break;
            case 1: $string.=$this->middlename." "; break;
            case 2: $string.=$this->middlename2." "; break;
            case 3: $string.=$this->middlename3." "; break;
            case 4: $string.=$this->middlename4." "; break;
            case 5: $string.=$this->middlename5." "; break;
        };

        $string.=(!empty ($this->birthname))? $this->birthname : $this->lastname;
        $string.="</span>";
        if(!empty ($this->birthday)){
            $string=$string."<br> ∗ ".$this->getBirthday()." ".$this->birthplace;
        }
        if(!empty ($this->deathdate)){
            $string=$string."<br>✝ ".$this->getDeathdate()." ".$this->deathplace;
        }
        return $string;
    }

    public function getDataForTreeSurnames(){
        $string="<span class='name'>";
        switch($this->usedname){
            case 0: $string.=$this->firstname; break;
            case 1: $string.=$this->middlename; break;
            case 2: $string.=$this->middlename2; break;
            case 3: $string.=$this->middlename3; break;
            case 4: $string.=$this->middlename4; break;
            case 5: $string.=$this->middlename5; break;
        };
        $string.=" ".$this->lastname."</span>";
        if(!empty ($this->birthname)){
            $string.=", geb.&nbsp;".$this->birthname;
        }
        if(!empty ($this->birthday)){
            $string=$string."<br> ∗ ".$this->getBirthday()." ".$this->birthplace;
        }
        if(!empty ($this->deathdate)){
            $string=$string."<br>✝ ".$this->getDeathdate()." ".$this->deathplace;
        }
        return $string;
    }

    public function getFullname(){
        $string ="<b>";
        switch($this->usedname){
            case 0: $string.=$this->firstname." <span class=heading2>".$this->middlename." ".$this->middlename2." ".$this->middlename3." ".$this->middlename4." ".$this->middlename5."</span> "; break;
            case 1: $string.="<span class=heading2>".$this->firstname." </span>".$this->middlename."<span class=heading2> ".$this->middlename2." ".$this->middlename3." ".$this->middlename4." ".$this->middlename5."</span> "; break;
            case 2: $string.="<span class=heading2>".$this->firstname." ".$this->middlename." </span>".$this->middlename2." <span class=heading2>".$this->middlename3." ".$this->middlename4." ".$this->middlename5."</span> "; break;
            case 3: $string.="<span class=heading2>".$this->firstname." ".$this->middlename." ".$this->middlename2." </span>".$this->middlename3." <span class=heading2>".$this->middlename4." ".$this->middlename5."</span> "; break;
            case 4: $string.="<span class=heading2>".$this->firstname." ".$this->middlename." ".$this->middlename2." ".$this->middlename3." </span>".$this->middlename4." <span class=heading2>".$this->middlename5."</span> "; break;
            case 5: $string.="<span class=heading2>".$this->firstname." ".$this->middlename." ".$this->middlename2." ".$this->middlename3." ".$this->middlename4." </span>".$this->middlename5." "; break;
        };
        $string.=$this->lastname;
        if(!empty ($this->birthname)){
            $string.=", geb.&nbsp;".$this->birthname;
        }
        return $string."</b>";
    }

    public function getPartner(){
        if(!empty($this->relationships))
            return $this->relationships[0];
        else return 0;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        if (!empty($this->birthday)){
            if ($this->birthdayAccuracy==0) return $this->birthday->format('d.m.Y');
            elseif ($this->birthdayAccuracy==1)  return $this->birthday->format('m.Y');
            elseif ($this->birthdayAccuracy==2)  return $this->birthday->format('Y');
            elseif ($this->birthdayAccuracy==3)  return "um ".$this->birthday->format('Y');
        }
        else return  null;
    }

    public function getChildren(){
        $children = array();
        foreach (Person::$allPersons as $person){
            if ($this==$person->getFather() || $this==$person->getMother()){
                $children[] = $person;
            }
        }
        usort($children, array($this, "cmp_age"));
        return $children;
    }

    public function getChildrenWithoutPartner(){
        $children = array();
        foreach (Person::$allPersons as $person){
            if (($this==$person->getFather() && $person->getMother()==null)|| ($this==$person->getMother() && $person->getFather()==null)){
                $children[] = $person;
            }
        }
        usort($children, array($this, "cmp_age"));
        return $children;
    }

    public function getBirthdate(){
        return $this->birthday;
    }
    static function cmp_age($a, $b){
        if ($a->getBirthdate() == $b->getBirthdate()) {
            return 0;
        }
        return ($a->getBirthdate() > $b->getBirthdate()) ? +1 : -1;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return mixed
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * @return mixed
     */
    public function getMiddlename()
    {
        return $this->middlenames;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated->format('d.m.Y');;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @return mixed
     */
    public function getDeathplace()
    {
        return $this->deathplace;
    }

    /**
     * @return mixed
     */
    public function getDeathdate()
    {
        if (!empty($this->deathdate)){
            if ($this->deathdateAccuracy==0) return $this->deathdate->format('d.m.Y');
            elseif ($this->deathdateAccuracy==1)  return $this->deathdate->format('m.Y');
            elseif ($this->deathdateAccuracy==2)  return $this->deathdate->format('Y');
            elseif ($this->deathdateAccuracy==3)  return "um ".$this->deathdate->format('Y');
        }
        else return  null;
    }

    /**
     * @return mixed
     */
    public function getBirthplace()
    {
        return $this->birthplace;
    }

    /**
     * @return mixed
     */
    public function getBirthname()
    {
        return $this->birthname;
    }

    /**
     * @return array
     */
    public function getRelationships(): array
    {
        usort($this->relationships,array($this,"cmp_marriage"));
        return ($this->relationships);
    }

    static function cmp_marriage(Relationship $a, Relationship $b){
        if ($a->getWeddingdateObject() == $b->getWeddingdateObject()) return 0;
        return ($a->getWeddingdateObject() > $b->getWeddingdateObject()) ? +1 : -1;
    }

    /**
     * @return mixed
     */
    public function getBaptised()
    {
        return empty($this->baptised) ? null : $this->baptised->format('d.m.Y');
    }

    /**
     * @return mixed
     */
    public function getDeathcause()
    {
        return $this->deathcause;
    }

    public function getLifetime(){
         $string = !empty($this->birthday) ? $this->birthday->format('Y') : "";
         $string.=" - ";
         $string.= !empty($this->deathdate)? $this->deathdate->format('Y') : "";
        return $string;
    }
    public function getAge(){
        return (!empty($this->birthday && !empty($this->deathdate))) ? ($this->birthday->diff($this->deathdate))->format(' %y Jahre') : null;
    }

    public function addImage(Image $image)
    {
        $this->images[] = $image;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }
}