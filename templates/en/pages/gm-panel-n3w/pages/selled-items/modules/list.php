<table>
    <tr>
        <th>DT</th>
        <th>Item</th>
        <th>Count</th>
        <th>Info</th>
        <th>From</th>
        <th>Action</th>
    </tr>
    <?php
    $stmt = $conn->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE CharID=:CharID AND ActionType=114 ORDER BY ActionTime DESC");
    $stmt->bindParam(':CharID', $CharID, PDO::PARAM_INT);
    $stmt->execute();
    while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ItemUID = $Item["Value1"];
        // Check if item exists
        $sql = "SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID=:ItemUID 
                UNION 
                SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID=:ItemUID 
                UNION 
                SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID=:ItemUID";
        $checkStmt = $conn->prepare($sql);
        $checkStmt->bindParam(':ItemUID', $ItemUID, PDO::PARAM_INT);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();
        if ($exists) {
            continue; // Skip if item exists
        }
        
        include("item.php");
    }
    ?>
</table>
