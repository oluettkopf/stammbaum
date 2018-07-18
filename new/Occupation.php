<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 15.07.2018
 * Time: 13:17
 */

class Occupation
{

    private static $allOccupations = array();
    private $id;
    private $term;
    private $femaleTerm;
    private $higherLevel;
    private $type;
    private $level;
    private $field;
    private $description;

    public function __construct($id, $term, $femaleTerm, $higherLevel, $type, $level, $field, $description)
    {
        $this->id=$id;
        $this->term=$term;
        $this->femaleTerm=$femaleTerm;
        $this->higherLevel=$higherLevel;
        $this->type=$type;
        $this->level=$level;
        $this->field=$field;
        $this->description=$description;
    }
}