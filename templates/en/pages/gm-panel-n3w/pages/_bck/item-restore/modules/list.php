<table>
    <tr>
        <th>UID</th>
        <th>UserID</th>
        <th>CharName</th>
        <th>Item</th>
        <th>DT</th>
        <th>Info</th>
        <th>By</th>
        <th>Action</th>
    </tr>
    <?php
    $stmt = $conn->prepare("SELECT TOP 100 * FROM PS_GameLog.dbo.BrokenItems WHERE Res=0 ORDER BY DT DESC");
    $stmt->execute();    
    while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$Item['UID']}</td>";
        echo "<td>{$Item['UserID']}</td>";
        echo "<td>{$Item['CharName']}</td>";
        echo "<td>{$Item['Item']}</td>";
        echo "<td>{$Item['DT']}</td>";
        echo "<td>{$Item['Info']}</td>";
        echo "<td>{$Item['By']}</td>";
        echo "<td>{$Item['Action']}</td>";
        echo "</tr>";
    }
    ?>
</table>
