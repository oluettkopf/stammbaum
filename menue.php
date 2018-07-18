<?php
namespace Stammbaum;
?>
<!DOCTYPE html>

<nav>
    <ul>
        <li><a href="index.php">Startseite</a></li>
        <li><a href="tree.php?id=<?php echo !empty($_GET['id']) ? $_GET['id'] : 1 ?>">Stammbaum</a></li>
        <li><a href="new?c=detail&id=<?php echo !empty($_GET['id']) ? $_GET['id'] : 1 ?>">Personendetails</a></li>
        <li><a href="form.php">Daten eingeben</a></li>
        <li><a href="places.php">Orte</a></li>
        <li><a href="info.php">Allgemeine Informationen</a></li>
    </ul>
</nav>
