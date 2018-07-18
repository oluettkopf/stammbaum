<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 03.02.2018
 * Time: 18:04
 */

//namespace genealogy;
include_once "Db.php";
include_once "Relationship.php";
include_once "Image.php";
include_once "Document.php";
//use \DateTime;

class Person
{
    private static $allPersons=array();
    private static $allData = array();

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
    private $documents = array();

    public function __construct(int $id, $firstname, $middlename, $middlename2, $middlename3, $middlename4, $middlename5,$lastname, $usedname,
                                $birthname,int $gender,$birthday, $birthdayAccuracy, $birthplace,$baptised,$deathdate, $deathdateAccuracy,
                                $deathplace,$deathcause,$occupation, $notes,$updated){
        $this->id=$id;
        $this->firstname=$firstname;
        $this->middlename=$middlename;
        $this->middlename2=$middlename2;
        $this->middlename3=$middlename3;
        $this->middlename4=$middlename4;
        $this->middlename5=$middlename5;
        $this->lastname=$lastname;
        $this->usedname=$usedname;
        $this->birthname=$birthname;
        $this->gender=$gender;
        if($birthday!='0001-01-01' && $birthday!='0000-00-00' && $birthday!=null) {
            $this->birthday = new DateTime($birthday);
        }
        $this->birthdayAccuracy=$birthdayAccuracy;
        $this->birthplace=$birthplace;
        $this->baptised=$baptised;
        if($deathdate!='0001-01-01' && $deathdate!='0000-00-00' && $deathdate!=null) {
            $this->deathdate = new DateTime($deathdate);
        }
        $this->deathdateAccuracy=$deathdateAccuracy;
        $this->deathplace=$deathplace;
        $this->deathcause=$deathcause;
        $this->occupation=$occupation;
        $this->notes=$notes;
        $this->updated=new DateTime($updated);

    }

    public static function allPersons(){
        $db = Db::getInstance();
        $result = $db->query("SELECT * FROM person");
        foreach ($result as $row){
            self::$allPersons[]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],$row['updated']);
        }
        return self::$allPersons;
    }

    public static function findById($id) {
        $db = Db::getInstance();
        $id = intval($id);
        $req = $db->prepare('SELECT * FROM person  /*INNER JOIN relationship r ON p.id=r.man OR p.id=r.wife*/ WHERE id = :id');
        $req->execute(array('id' => $id));
        $row = $req->fetch();

        $person = new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
            $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
            $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
            $row['updated']);

        if(!empty($row['father'])){
            $person->setFather(Person::findById($row['father']));
        }
        if(!empty($row['mother'])){
            $person->setMother(Person::findById($row['mother']));
        }
        $person->setRelationships(Relationship::find($person->getGender(),$person->getId()));

        return $person;
    }

    public static function findByQuery( $value){
        $db = Db::getInstance();

        $req = $db->prepare('SELECT * FROM person WHERE id = :v');
        $req->execute(array('v'=>$value));
        $row = $req->fetch();

        return new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
            $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
            $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
            $row['updated']);
    }

    public static function getAllData(){
        $db = Db::getInstance();
        $result = $db->query("SELECT * FROM person");
        $father = array();
        $mother = array();
        foreach ($result as $row){
            self::$allData[$row['id']]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],$row['updated']);
            if(!empty($row['father'])){
                $father[] = array(intval($row['id']),intval($row['father']));
            }
            if(!empty($row['mother'])){
                $mother[] = array(intval($row['id']),intval($row['mother']));
            }
        }
        foreach ($father as $row){
            self::$allData[$row[0]]->setFather(self::$allData[$row[1]]);
        }
        foreach ($mother as $row){
            self::$allData[$row[0]]->setMother(self::$allData[$row[1]]);
        }
        $result = $db->query("SELECT * FROM relationship");
        foreach ($result as $row) {
            self::$allData[$row['man']]->addRelationship(new Relationship(intval($row['id']),self::$allData[$row['wife']],$row['married'],$row['weddingdate'],$row['weddingAccuracy'],$row['weddingplace'],$row['divorce'],$row['notes']));
            self::$allData[$row['wife']]->addRelationship(new Relationship(intval($row['id']),self::$allData[$row['man']],$row['married'],$row['weddingdate'],$row['weddingAccuracy'],$row['weddingplace'],$row['divorce'],$row['notes']));
        }

        $result = $db->query("SELECT * FROM images i inner join person_in_image p on p.imgId=i.id");
        foreach ($result as $row) {
            $image=array_key_exists($row['imgId'],Image::getAllImages()) ? Image::getAllImages()[intval($row['imgId'])] : new Image(intval($row['imgId']),$row['url'],$row['extension'],$row['description'],$row['year'],$row['yearAccuracy'],$row['imgtype']);
            self::$allData[$row['personId']]->addImage($image);
            Image::getAllImages()[intval($row['imgId'])]->addPerson($row['personId'],$row['notesimg']);
        }
        $result = $db->query("SELECT * FROM documents d inner join person_in_document p on p.docId=d.id");
        foreach ($result as $row) {
            $document=array_key_exists($row['docId'],Document::getAllDocuments()) ? Document::getAllDocuments()[intval($row['docId'])] : new Document(intval($row['docId']),$row['url'],$row['extension'],$row['description'],$row['year'],$row['yearAccuracy']);
            self::$allData[$row['personId']]->addDocument($document);
            Document::getAllDocuments()[intval($row['docId'])]->addPerson($row['personId'],$row['docnotes']);
        }

        return self::$allData;
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


    public function setRelationships($relationships)
    {
        $this->relationships=$relationships;
        /*$db= Db::getInstance();
        if($this->gender==0) {
            $req = $db->prepare("SELECT * FROM relationship WHERE man = :id");
            if($req->execute(array('id' => $this->id))) {
                while ($row = $req->fetch()) {
                    $this->relationships[] = new Relationship($row['id'], $row['wife'], $row['married'], $row['weddingdate'], $row['weddingAccuracy'], $row['weddingplace'], $row['divorce'], $row['notes']);
                }
            }
            else {
                echo "SQL Error <br />";
                echo $req->queryString . "<br />";
                echo $req->errorInfo()[2];
            }
        }
        else{
            $req = $db->prepare("SELECT * FROM relationship WHERE wife = :id");
            if($req->execute(array('id' => $this->id))) {
                while ($row = $req->fetch()) {
                    $this->relationships[] = new Relationship($row['id'], $row['man'], $row['married'], $row['weddingdate'], $row['weddingAccuracy'], $row['weddingplace'], $row['divorce'], $row['notes']);
                }
            }
            else {
                echo "SQL Error <br />";
                echo $req->queryString . "<br />";
                echo $req->errorInfo()[2];
            }
        }*/
    }


    public function addRelationship($relationship){
        $this->relationships[]=$relationship;
    }
    /**
     * @param mixed $father
     */
    public function setFather($father)
    {
        $this->father = $father;
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

    /**
     * @param mixed $mother
     */
    public function setMother($mother)
    {
        $this->mother = $mother;
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
    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getFirstnames(){
        return $this->firstname." ".$this->middlename." ".$this->middlename2." ".$this->middlename3." ".$this->middlename4." ".$this->middlename5;
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
    public function getBirthname()
    {
        return $this->birthname;
    }

    public function getFullname(){
        $name = array();
        switch ($this->usedname){
            case 0: $name=array($this->firstname=>1, $this->middlename=>0, $this->middlename2=>0, $this->middlename3=>0, $this->middlename4=>0, $this->middlename5=>0);
                break;
            case 1: $name=array($this->firstname=>0, $this->middlename=>1, $this->middlename2=>0, $this->middlename3=>0, $this->middlename4=>0, $this->middlename5=>0);
                break;
            case 2: $name=array($this->firstname=>0, $this->middlename=>0, $this->middlename2=>1, $this->middlename3=>0, $this->middlename4=>0, $this->middlename5=>0);
                break;
            case 3: $name=array($this->firstname=>0, $this->middlename=>0, $this->middlename2=>0, $this->middlename3=>1, $this->middlename4=>0, $this->middlename5=>0);
                break;
            case 4: $name=array($this->firstname=>0, $this->middlename=>0, $this->middlename2=>0, $this->middlename3=>0, $this->middlename4=>1, $this->middlename5=>0);
                break;
            case 5: $name=array($this->firstname=>0, $this->middlename=>0, $this->middlename2=>0, $this->middlename3=>0, $this->middlename4=>0, $this->middlename5=>1);
                break;
        }
        $name[$this->lastname] = 1 ;

        return $name;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        if ($this->birthday != null){
            $monthNamesDE = [
                'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
            ];
            switch ($this->birthdayAccuracy) {
                case 0:
                    return $this->birthday->format('d.m.Y');
                    break;
                case 1:
                    return $monthNamesDE[$this->birthday->format('n')-1]." ".$this->birthday->format('Y');
                    break;
                case 2:
                    return $this->birthday->format('Y');
                    break;
                case 3:
                    return "um " . $this->birthday->format('Y');
                    break;
                default:
                    return null;
                    break;
            }
        }
    }

    /**
    * @return mixed
    */
    public function getDeathdate()
    {
        if ($this->deathdate != null){
            $monthNamesDE = [
                'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
            ];
            switch ($this->deathdateAccuracy) {
                case 0:
                    return $this->deathdate->format('d.m.Y');
                    break;
                case 1:
                    return $monthNamesDE[$this->deathdate->format('n')-1]." ".$this->deathdate->format('Y');
                    break;
                case 2:
                    return $this->deathdate->format('Y');
                    break;
                case 3:
                    return "um " . $this->deathdate->format('Y');
                    break;
                default:
                    return null;
                    break;
            }
        }
    }

    public function getAge(){
        return (!empty($this->birthday) && !empty($this->deathdate)) ? $this->birthday->diff($this->deathdate)->format('%y Jahre') : null;
    }

    /**
     * @return mixed
     */
    public function getDeathcause()
    {
        return $this->deathcause;
    }

    /**
     * @return mixed
     */
    public function getBaptised()
    {
        return ($this->baptised == '0000-00-00' || $this->baptised=='001-01-01') ? null : $this->baptised;
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
    public function getDeathplace()
    {
        return $this->deathplace;
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
    public function getUpdated()
    {
        return $this->updated;
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
    public function getNotes()
    {
        if(!empty($this->notes)) {
            $array = explode("- ", $this->notes);
            if (count($array) > 1) {
                array_shift($array);
            }
            return $array;
        }
        else return null;
    }

    public function __toString(){
        $string = $this->getFirstnames()." ";
        $string.= !empty($this->birthname)? $this->birthname : $this->lastname;
        //$string.=" (".$this->getBirthday()." - ".$this->getDeathdate().")";
        $title = array('Freiherr','Freiin');
        $string = preg_replace('/\b('.implode('|',$title).')\b/','',$string);
        return $string;
    }

    public function getLifetime(){
        $string = " (";
        $string.= (!empty($this->birthday)) ? $this->birthday->format('Y')." - " : " - ";
        $string.= (!empty($this->deathdate)) ? $this->deathdate->format('Y').")" : ")";
        return $string;
    }

    public function getChildrenParter($partner){
        $children=array();
        $partner=intval($partner);
        $db=Db::getInstance();
        if ($this->gender==0) {
            $req = $db->prepare('SELECCT * FROM person WHERE father = :id and mother = :partner');
            $req->execute(array(':id'=>$this->id, ':partner'=>$partner));
            while ($row = $req->fetch()){
                $children[]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                    $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                    $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
                    $row['updated']);
            }
        }
        else {
            $req = $db->prepare('SELECCT * FROM person WHERE father = :partner and mother = :id');
            $req->execute(array('id'=>$this->id, 'partner'=>$partner));
            while ($row = $req->fetch()){
                $children[]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                    $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                    $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
                    $row['updated']);
            }
        }
        return $children;
    }

    public function getChildren(){
        $children=array();
        $db=Db::getInstance();
        $req=null;
        if ($this->gender==0) {
            $req = $db->prepare('SELECT * FROM person WHERE father = :id and mother is null');
            $req->execute(array('id' => $this->id));
        }
        else {
            $req = $db->prepare('SELECT * FROM person WHERE mother = :id and father is null ');
            $req->execute(array(':id' => $this->getId()));
        }
        while ($row = $req->fetch()){
            $children[]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
                $row['updated']);
        }
        usort($children, array($this, "cmp_age"));
        return $children;
    }
    public function getBirthdate(){
        return $this->birthday;
    }
    public static function cmp_age($a, $b){
        if ($a->getBirthdate() == $b->getBirthdate()) {
            return 0;
        }
        return ($a->getBirthdate() > $b->getBirthdate()) ? +1 : -1;
    }

    public function getSiblings(){
        $siblings=array();
        $db=Db::getInstance();
        $req=null;
        $req = $db->prepare('SELECT * FROM person WHERE father = :father and mother = :mother and id != :id');
        $req->execute(array('father' => (!empty($this->father)) ? $this->father->getId() : null, 'mother' =>(!empty($this->mother)) ? $this->mother->getId() : null, 'id' => $this->id));
        while ($row = $req->fetch()){
            $siblings[]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
                $row['updated']);
        }
        usort($siblings, array($this, "cmp_age"));
        return $siblings;
    }

    public function getHalfsiblingsFather(){
        if($this->father == null){
            return null;
        }
        $siblings=array();
        $db=Db::getInstance();
        $req=null;
        if($this->mother!= null) {
            $req = $db->prepare('SELECT * FROM person WHERE father = :father and (mother != :mother or mother is null)');
            $req->execute(array('father' => $this->father->getId(), 'mother' => $this->mother->getId()));
        }
        else{
            $req = $db->prepare('SELECT * FROM person WHERE father = :father and mother is not null');
            $req->execute(array('father' => $this->father->getId()));
        }
        while ($row = $req->fetch()){
            $siblings[]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
                $row['updated']);
        }
        usort($siblings, array($this, "cmp_age"));
        return $siblings;
    }

    public function getHalfsiblingsMother(){
        if($this->mother==null){
            return null;
        }
        $siblings=array();
        $db=Db::getInstance();
        $req=null;
        if($this->father!=null) {
            $req = $db->prepare('SELECT * FROM person WHERE (father != :father or father is null) and mother = :mother');
            $req->execute(array('father' => $this->father->getId(), 'mother' =>  $this->mother->getId()));
        }
        else{
            $req = $db->prepare('SELECT * FROM person WHERE father is not null and mother = :mother');
            $req->execute(array('mother' => $this->mother->getId()));
        }
        while ($row = $req->fetch()){
            $siblings[]=new Person($row['id'],$row['firstName'],$row['middleName'],$row['middleName2'],$row['middleName3'],$row['middleName4'],
                $row['middleName5'],$row['lastName'],$row['usedName'],$row['birthName'],$row['gender'],$row['birthday'],$row['birthdayAccuracy'],$row['birthplace'],
                $row['baptised'],$row['deathdate'],$row['deathdateAccuracy'],$row['deathplace'],$row['deathcause'],$row['occupation'],$row['notes'],
                $row['updated']);
        }
        usort($siblings, array($this, "cmp_age"));
        return $siblings;
    }

    public function getPortrait(){
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * from images i INNER JOIN person_in_image p ON i.id = p.imgId WHERE p.personId = :id AND i.imgtype = 0");
        $req->execute(array('id'=>$this->id));
        $row = $req->fetch();
        $image = !empty($row) ? new Image($row['id'],$row['url'],$row['extension'],$row['description'],$row['year'],$row['yearAccuracy'],$row['imgtype'],$row['notesimg']) : null;
        return $image;
    }

    public function getImages(){
        $images = array();
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * from images i INNER JOIN person_in_image p ON i.id = p.imgId WHERE p.personId = :id");
        $req->execute(array('id'=>$this->id));
        while ($row = $req->fetch()) {
            $images[] = new Image($row['id'],$row['url'], $row['extension'], $row['description'], $row['year'],$row['yearAccuracy'], $row['imgtype'], $row['notesimg']);
        }
        usort($images,function (Image $a, Image $b){
           if ($a->getYearOnly()==$b->getYearOnly()) return 0;
           else return $a->getYearOnly()>$b->getYearOnly()? 1 : -1;
        });
        return $images;
    }
    public function addImage($image){
        $this->images[]=$image;
    }

    public function addDocument($document){
        $this->documents[]=$document;
    }

    public function getImages2(){

        usort($this->images,function (Image $a, Image $b){
            if ($a->getYearOnly()==$b->getYearOnly()) return 0;
            else return $a->getYearOnly()>$b->getYearOnly()? 1 : -1;
        });
        return $this->images;
    }

    /**
     * @return array
     */
    public function getDocuments(): array
    {
        usort($this->documents,function (Document $a, Document $b){
            if ($a->getYearOnly()==$b->getYearOnly()) return 0;
            else return $a->getYearOnly()>$b->getYearOnly()? 1 : -1;
        });
        return $this->documents;
    }
}