<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Pulizia dei dati di input
$category = isset($_POST['c']) ? intval($_POST['c']) : 0;
$map = isset($_POST['m']) ? intval($_POST['m']) : 0;
$itemID = isset($_POST['i']) ? intval($_POST['i']) : 0;

// Definizione delle condizioni
$excludedGrades = [0, 30, 39, 40, 99, 335, 336, 442, 815, 999]; // Aggiungi gli ID da escludere
$gradeCondition = ' AND i.Grade NOT IN (' . implode(', ', $excludedGrades) . ')';

$typeConditions = [
    1 => [16, 17, 18, 19, 20, 21, 31, 32, 33, 34, 35, 36],
    2 => range(1, 65),
    3 => [30],
    4 => [22, 23, 40],
    5 => [24, 39],
    6 => [42],
    7 => [25, 38, 41, 43, 44, 100],
    8 => [95]
];
$typeCondition = isset($typeConditions[$category]) ? ' AND i.Type IN (' . implode(', ', $typeConditions[$category]) . ')' : '';

$mapCondition = $map != 100 ? " AND mn.MapID = :mapID" : '';

$query = "SELECT i.ItemID, i.ItemName
    FROM PS_GameDefs.dbo.MobItems mi 
    INNER JOIN PS_GameDefs.dbo.Mobs m ON m.MobID = mi.MobID 
    INNER JOIN PS_GameDefs.dbo.MapNames mn ON m.MapID = mn.MapID
    INNER JOIN PS_GameDefs.dbo.Items i ON mi.Grade = i.Grade
    WHERE mi.MobID != 0 $gradeCondition $typeCondition $mapCondition
    GROUP BY i.ItemID, i.ItemName ORDER BY i.ItemName ASC";

$search = $conn->prepare($query);

try {
    if ($map != 100) {
        $search->bindParam(':mapID', $map, PDO::PARAM_INT);
    }

    $search->execute();

    while ($item = $search->fetch(PDO::FETCH_NUM)) {
        $itemName = htmlspecialchars($item[1], ENT_QUOTES, 'UTF-8');
        $select = $itemID == $item[0] ? 'selected' : '';
        echo "<option value='{$item[0]}' $select>$itemName</option>";
    }
} catch (PDOException $e) {
    // Gestione degli errori del database
    echo "Errore nel recupero dei dati: " . $e->getMessage();
}
?>
