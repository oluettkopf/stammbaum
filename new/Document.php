<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 22.04.2018
 * Time: 13:40
 */

class Document
{
    private static $allDocuments = array();
    private $id;
    private $url;
    private $description;
    private $year;
    private $yearAccuracy;
    private $persons = array();

    public function __construct($id, $url,$extension,$description,$year,$yearAccuracy)
    {
        $this->id=$id;
        $this->url=$url.".".$extension;
        $this->description=$description;
        $this->year=$year;
        $this->yearAccuracy=$yearAccuracy;
        self::$allDocuments[$id]=$this;
    }

    /**
     * @return mixed
     */
    public static function getAllDocuments()
    {
        return self::$allDocuments;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getPersons(): array
    {
        return $this->persons;
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
     * @param array $persons
     */
    public function addPerson($person,$note): void
    {
        $this->persons[$person] = $note;
    }

}