<?php

/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 09.02.2018
 * Time: 12:56
 */
class Image
{
    private static $allImages=array();
    private $id;
    private $url;
    private $description;
    private $year;
    private $type;
    private $personnotes;
    private $yearAccuracy;
    private $persons=array();

    public function __construct($id,$url,$extension,$description,$year,$yearAccuracy,$type)
    {
        $this->id=$id;
        $this->url=$url.".".$extension;
        $this->description=$description;
        $this->year=$year;
        $this->yearAccuracy=$yearAccuracy;
        $this->type=$type;
        //$this->personnotes=$personnotes;
        self::$allImages[$id]=$this;
    }

    public static function getAllImages(){
        return self::$allImages;
    }
    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        switch ($this->yearAccuracy){
            case 0: return $this->year; break;
            case 1: return "ca. ".$this->year; break;
            case 2: return null; break;
        }

    }

    public function getYearOnly(){
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function __toString()
    {
        return $this->description." ".$this->year;
    }

    public function getPersons(){
        return $this->persons;
    }

    public function addPerson($person, $note){
        $this->persons[$person]=$note;
    }


}