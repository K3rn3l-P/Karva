<?php
$result = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.Tiered_Spender_User_Progress WHERE UserUID=$UserUID AND SpenderID=$SpenderID");
if (!odbc_num_rows($result)) {
    odbc_exec($odbcConn, "INSERT INTO PS_WebSite.dbo.Tiered_Spender_User_Progress VALUES ($UserUID, $SpenderID, 0)");
    $result = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.Tiered_Spender_User_Progress WHERE UserUID=$UserUID AND SpenderID=$SpenderID");
}
$Spended = odbc_result($result, "DP");

$query = "SELECT R.*, RewardItemID FROM PS_WebSite.dbo.Tiered_Spender_Reward R
        LEFT JOIN PS_WebSite.dbo.Tiered_Spender_User_Reward UR ON UR.UserUID=$UserUID AND UR.RewardID=R.ID
        WHERE R.SpenderID=$SpenderID ORDER BY [AP]";
$result = odbc_exec($odbcConn, $query);
$RewardsCount = odbc_num_rows($result);

$Rewards = array();
while ($Reward = odbc_fetch_array($result)) {
    $Reward["Available"] = $Reward["AP"] <= $Spended && !$Reward["RewardItemID"];
    $Rewards[] = $Reward;
}
?>
<div id="form_wrapper">
    <div id="form_tiered_spender_display">
        <div id="tiered_spender_wrapper">
            <div id="tracker_container">
                <?php include("tiered-spender-progress.php") ?>
                <?php include("tiered-spender-rewards.php") ?>
            </div>
        </div>
    </div>
</div>