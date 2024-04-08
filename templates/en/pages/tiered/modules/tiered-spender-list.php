<?php
// Preparazione della query SQL
$query = "SELECT * FROM PS_WebSite.dbo.Tiered_Spender WHERE CURRENT_TIMESTAMP BETWEEN StartDate AND EndDate ORDER BY [ID]";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Elaborazione dei risultati
while ($Info = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $isActive = $spenderID == $Info["ID"] ? "active" : "";
    $menuIconUrl = $AssetUrl . "/images/tieredspender/icons/" . $Info["Image"];
    ?>
    <li class="leaf">
        <a href="?p=<?= $page ?>&id=<?= $Info["ID"] ?>" title="<?= $Info["Name"] ?>" class="pad-30-l <?= $isActive ?>">
            <?= $Info["Name"] ?>
            <span class="menu_icon" style="background-image:url(<?= $menuIconUrl ?>)"></span>
        </a>
    </li>
    <?php
}
?>
