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
    $stmt = $conn->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE CharID = ? AND ActionType = 114 ORDER BY ActionTime DESC");
    $stmt->bindValue(1, $CharID, PDO::PARAM_INT);
    $stmt->execute();
    while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ItemUID = $Item["Value1"];
        
        // Check if the item exists in any of the tables
        $tables = ['CharItems', 'UserStoredItems', 'MarketItems'];
        $itemExists = false;
        foreach ($tables as $table) {
            $query = $conn->prepare("SELECT COUNT(*) AS count FROM PS_GameData.dbo.$table WHERE ItemUID = ?");
            $query->bindValue(1, $ItemUID, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            if ($row && $row['count'] > 0) {
                $itemExists = true;
                break;
            }
        }

        if ($itemExists) {
            continue;
        }

        include("item.php");
    }
    ?>
</table>
