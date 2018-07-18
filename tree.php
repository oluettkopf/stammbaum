<?php
namespace Stammbaum;
include_once('functions.php');
include_once ('initialize.php');
include_once ('Person.php');
include_once ('Relationship.php');
include_once ('menue.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/tree_style.css">
</head>
<body>
<main>
<?php
$id=$_GET['id'];
$p=Person::getAllPersons()[$id];
$link="href='tree.php?id=";
?>




<br>
<div class="container">
    <a class="greatgrandparentsM"<?php echo ($p->getFather() && $p->getFather()->getFather() && $p->getFather()->getFather()->getFather()) ? $link.$p->getFather()->getFather()->getFather()->getId()."'"." >".$p->getFather()->getFather()->getFather()->getDataForTree() : ">" ?></a>
    <a class="greatgrandparentsF"<?php echo ($p->getFather() && $p->getFather()->getFather() && $p->getFather()->getFather()->getMother()) ? $link.$p->getFather()->getFather()->getMother()->getId()."'"." > ".$p->getFather()->getFather()->getMother()->getDataForTree() : ">" ?></a>
    <a class="greatgrandparentsM"<?php echo ($p->getFather() && $p->getFather()->getMother() && $p->getFather()->getMother()->getFather()) ? $link.$p->getFather()->getMother()->getFather()->getId()."'"." > ".$p->getFather()->getMother()->getFather()->getDataForTree() : ">" ?></a>
    <a class="greatgrandparentsF"<?php echo ($p->getFather() && $p->getFather()->getMother() && $p->getFather()->getMother()->getMother()) ? $link.$p->getFather()->getMother()->getMother()->getId()."'"." > ".$p->getFather()->getMother()->getMother()->getDataForTree() : ">" ?></a>
    <a class="greatgrandparentsM"<?php echo ($p->getMother() && $p->getMother()->getFather() && $p->getMother()->getFather()->getFather()) ? $link.$p->getMother()->getFather()->getFather()->getId()."'"." >".$p->getMother()->getFather()->getFather()->getDataForTree() : ">" ?></a>
    <a class="greatgrandparentsF"<?php echo ($p->getMother() && $p->getMother()->getFather() && $p->getMother()->getFather()->getMother()) ? $link.$p->getMother()->getFather()->getMother()->getId()."'"." >".$p->getMother()->getFather()->getMother()->getDataForTree() : ">" ?></a>
    <a class="greatgrandparentsM"<?php echo ($p->getMother() && $p->getMother()->getMother() && $p->getMother()->getMother()->getFather()) ? $link.$p->getMother()->getMother()->getFather()->getId()."'"." >".$p->getMother()->getMother()->getFather()->getDataForTree() : ">" ?></a>
    <a class="greatgrandparentsF"<?php echo ($p->getMother() && $p->getMother()->getMother() && $p->getMother()->getMother()->getMother()) ? $link.$p->getMother()->getMother()->getMother()->getId()."'"." >".$p->getMother()->getMother()->getMother()->getDataForTree() : ">" ?></a>
</div>

<div class="container">
<a id='grandfather1'<?php echo ($p->getFather() && $p->getFather()->getFather()) ? $link.$p->getFather()->getFather()->getId()."'"." >".$p->getFather()->getFather()->getDataForTree() : "> " ?> </a>
<a id="grandmother1"<?php echo ($p->getFather() && $p->getFather()->getMother()) ? $link.$p->getFather()->getMother()->getId()."'"."> ".$p->getFather()->getMother()->getDataForTree() : "> " ?></a>
<a id="grandfather2"<?php echo ($p->getMother() && $p->getMother()->getFather()) ? $link.$p->getMother()->getFather()->getId()."'"." > ".$p->getMother()->getFather()->getDataForTree() : "> " ?> </a>
<a id="grandmother2"<?php echo ($p->getMother() && $p->getMother()->getMother()) ? $link.$p->getMother()->getMother()->getId()."'"." > ".$p->getMother()->getMother()->getDataForTree() : "> " ?> </a>
</div>
<div class="container">
    <div class="grandparents"></div><div class="grandparents"></div>
    <div class="grandparents2"></div><div class="grandparents2"></div>

<a <?php  echo $p->getFather() ? $link.$p->getFather()->getId()."'" : "" ?> id="father"><?php echo $p->getFather() ? $p->getFather()->getDataForTree() : "" ?>

<div id="fathertooltip">
    <?php
    if(!empty($p->getFather())) {
        $father = $p->getFather();
        echo "<b>".$father->getFullname() . "</b><br>";
        echo !empty($father->getOccupation()) ? "Beruf: ".$father->getOccupation() . "<br>" : "";
        echo "Kinder: ";
        foreach ($father->getChildren() as $child) {
            echo $child->getFullname() . ", <br>";
        }
    }
    ?>
</div>


</a>



    <a <?php echo $p->getMother() ? $link.$p->getMother()->getId()."'" : "" ?> id="mother"><?php echo $p->getMother() ? $p->getMother()->getDataForTree() : "" ?> </a>
</div>
<div id="parents"> </div><div id="parents2"></div>

<div id="active" > <?php echo $p->getDataForTree();?></div>

<p class="partner">
<?php foreach ($p->getRelationships() as $partner){?>
<a <?php echo $link.$partner->getPartner()->getId()."'"; ?> class='wife' >
             <?php echo $partner->getWedding() . "<br>" . $partner->getPartner()->getDataForTree()."</a>"; ?>


    <br>
    <p class="children2">
    <?php
    foreach ($partner->getChildren($p) as $child) {
         $string="<a href='tree.php?id=" . $child->getId() . "' class='children relative' >" . $child->getDataForTree();
        if(!empty($child->getChildren())){
            $string.=count($child->getChildren())>1 ? "<br><br><span class='small absolute'> ".count($child->getChildren())." Kinder</span>" : "<br><br><span class='small absolute'>1 Kind</span>";

        }
        echo $string."</a>";
    }
    echo "</p>";
}
echo "<p class=\"children2\">";
foreach($p->getChildrenWithoutPartner() as $child){
    $string="<a href='tree.php?id=" . $child->getId() . "' class='children relative' >" . $child->getDataForTree();
    if(!empty($child->getChildren())){
        $string.=count($child->getChildren())>1 ? "<br><br><span class='small absolute'> ".count($child->getChildren())." Kinder</span>" : "<br><br><span class='small absolute'> 1 Kind</span>";

    }
    echo $string."</a>";
}
echo "</p>";
?>
</p>

<div id="tooltip"></div>
</main>
<footer>
<br><br>
</footer>


<script>
    /*
    var array = document.getElementsByClassName('arrow');




    function showInformation(name,occupation){
	    document.getElementById("tooltip").innerHTML=name+"<br>"+occupation;
	    document.getElementById("tooltip").style.display='inline-block';
    }
    function hideInformation(){
        document.getElementById('tooltip').style.display='none';
    }*/

</script>

</body>
</html>