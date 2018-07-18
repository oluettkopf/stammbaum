<?php
/**
 * Created by PhpStorm.
 * User: Ortrun
 * Date: 15.07.2018
 * Time: 13:07
 */

class Place
{
    private static $allPlaces = array();
    private $id;
    private $name;
    private $level;
    private $higherlevel;
    private $sublevels = array();
    private $personsInPlace = array();
    private $description;

    public function __construct($id, $name, $level, $higherLevel, $description)
    {
        $this->id=$id;
        $this->name=$name;
        $this->level=$level;
        $this->higherlevel=$higherLevel;
        $this->description=$description;
    }

    public static function findById($id){
        $db = Db::getInstance();
        $id = intval($id);
        $req = $db->prepare('SELECT p.*,n.id as id2, n.name as name2, n.level as level2, n.next_level as next_level2, n.description as description2 FROM place p left join place n on p.next_level=n.id WHERE p.id = :id');
        $req->execute(array('id' => $id));
        $row = $req->fetch();

        $place = new Place($row['id'],$row['name'],$row['level'],$row['next_level'],$row['description']);
        if(!empty($place->getHigherlevel())){
            $place->setHigherlevel(new Place($row['id2'],$row['name2'],$row['level2'],$row['next_level2'],$row['description2']));
        }

        return $place;
    }

    public static function findAllPlaces(){
        $db = Db::getInstance();
        $result = $db->query("SELECT * FROM place ORDER BY name");
        foreach ($result as $row){
            self::$allPlaces[] = new Place($row['id'],$row['name'],$row['level'],$row['next_level'],$row['description']);
        }
        foreach (self::$allPlaces as $place){
            if(!empty($place->getHigherlevel())){
                $place->setHigherlevel()
            }
        }
        return self::$allPlaces;
    }

    /**
     * @return mixed
     */
    public function getHigherlevel()
    {
        return $this->higherlevel;
    }

    /**
     * @param mixed $higherlevel
     */
    public function setHigherlevel($higherlevel): void
    {
        $this->higherlevel = $higherlevel;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
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
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

}