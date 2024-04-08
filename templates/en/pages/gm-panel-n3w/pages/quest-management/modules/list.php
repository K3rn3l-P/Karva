<table>
    <tr>
        <th>QuestID</th>
        <th>Count1</th>
        <th>Count2</th>
        <th>Count3</th>
        <th>Success</th>
        <th>Finish</th>
        <th>Action</th>
    </tr>
    <?php
    $stmt = $conn->prepare("SELECT * FROM PS_GameData.dbo.CharQuests WHERE CharID=:CharID AND Del=0");
    $stmt->bindParam(':CharID', $CharID, PDO::PARAM_INT);
    $stmt->execute();
    while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rowId = $Item["RowID"];
        include("item.php");
    }
    ?>
</table>
