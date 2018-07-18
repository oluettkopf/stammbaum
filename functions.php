<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function reverse_date($string,$a){
    if ($string!="" && $string!='0000-00-00'){
        $date = new DateTime($string);
        if ($a==0) return $date->format('d.m.Y');
        elseif ($a==1)  return $date->format('m.Y');
        elseif ($a==2)  return $date->format('Y');
        elseif ($a==3)  return "um ".$date->format('Y');
    }
    else return "";
}
/*
function test_date($string){
    if (!preg_match("/^[0-9]{2}.[0-9]{2}.[0-9]{4}$/",$string)) {
        $birthdayErr="ungültiger Eintrag!";
    }
    else {
        $comp=explode('.',$string);
        if( !checkdate( intval($comp[1]), intval($comp[0]), intval($comp[2]))){
            $birthdayErr="ungültiges Datum!";
        }
    }
    return $birthdayErr;
}
function convert_datestring($string) {
	$comp=explode('.',$string);			
	$date=new DateTime($comp[2].'-'.$comp[1].'-'.$comp[0]);
	return $date->format("Y-m-d");
}

function reverse_date($string){
	if ($string!="" && $string !="0000-00-00"){
		if (preg_match("/^[0-9]{4}$/",$string)) return $string;
	    $date = new DateTime($string);
		return $date->format('d.m.Y');
	}
	else return "";
}

function calculateAge($born,$death)
{

    $start = new DateTime($born);
    $end = new DateTime($death);
    return ($start->diff($end))->format('%y Jahre alt');
}


function get_father($id,$db){
	$fatherid=0;
	if($id!=0){
	$sql="SELECT person1 FROM relationship WHERE relkind=3 and person2=".$id;
	$father=$db->query($sql);
	if ($father->num_rows > 0) {
		while($row = $father->fetch_assoc()) {
			$fatherid=$row['person1'];
		}
	}}
	return $fatherid;
}


function get_partner($id,$db){
	$partnerid=0;
	$sql="SELECT person1,person2 FROM relationship WHERE relkind=1 and (person2=".$id." or person1=".$id.")";
	$partner=$db->query($sql);
	if ($partner->num_rows > 0) {
		while($row = $partner->fetch_assoc()) {
			if($id!=$row['person1'])$partnerid=$row['person1'];
			else $partnerid=$row['person2'];
		}
	}
	return $partnerid;
}

function get_children_man($id,$db){
	$sql="SELECT person2 FROM relationship WHERE relkind=3 and person1=".$id;
	$result=$db->query($sql);
	$children=array();
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$children[]=$row['person2'];
		}
	}
	
	return $children;
}

*/

?>